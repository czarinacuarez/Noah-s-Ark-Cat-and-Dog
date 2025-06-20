<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerApplication;
use App\Models\Application;
use App\Models\VolunteerAnswers;
use App\Models\Notifications;
use App\Models\ScheduleInterview;
use App\Models\User;
use App\Models\SchedulePickup;
use App\Exports\VolunteersExport;
use Maatwebsite\Excel\Facades\Excel;

class VolunteerController extends Controller
{
    public function store(Request $request, $userId)
    {
        $currentUserId = auth()->user()->id;
        $adminId = User::where('role', 'admin')->value('id');;

        // Check for an existing application for the current user
        $existingApplication = VolunteerApplication::join('application', 'volunteer_application.application_id', '=', 'application.id')
            ->where('application.user_id', $currentUserId)
            ->whereNotIn('volunteer_application.stage', [10, 5, 11])
            ->select('volunteer_application.*')
            ->first();
            
        if ($existingApplication) {
            // If an existing application is found, redirect back with a message
            return redirect()->back()->with(['already_submitted' => true]);
        }
    
        // Create the main application record
        $application = new Application();
        $application->user_id = $currentUserId;
        $application->application_type = 'application_volunteer';
        $application->save();
    
        // Create the VolunteerApplication record
        $volunteerApplication = new VolunteerApplication();
        $volunteerApplication->application_id = $application->id;
        $volunteerApplication->stage = 0;
        $volunteerApplication->save();
    
        $volunteerApplicationId = $volunteerApplication->id; // Use the correct property name
    
        // Store the answers
        $answers = $request->except('_token');
        $serializedAnswers = json_encode($answers);
    
        VolunteerAnswers::create([
            'volunteer_id' => $volunteerApplicationId,
            'answers' => $serializedAnswers
        ]);

        if (auth()->check()) {
            $user = auth()->user();      
        
            $notificationMessage = 'has submitted a volunteer application.';

            $notification = new Notifications();
            $notification->application_id = $application->id;;
            $notification->sender_id = $currentUserId;
            $notification->receiver_id = $adminId; 
            $notification->concern = 'Volunteer Application';
            $notification->message = $notificationMessage;
            $notification->save();
        } else {
            
        }
    
        return redirect()->route('user.volunteerprogress', ['userId' => $userId, 'applicationId' => $application->id])->with(['send_volunteer_form' => true]);
    }
    
    public function showVolunteer(Request $request)
    {
        $volunteer = VolunteerAnswers::paginate(10);
        $volunteerCount = VolunteerApplication::count();
        $volunteerPendingCount = $volunteer->where('volunteer_application.stage', '>=', 0)
        ->where('volunteer_application.stage', '<=', 4)
        ->count();
        $volunteerApprovedCount = $volunteer->where('volunteer_application.stage', '==', 5)
        ->count();
        $volunteerRejectedCount = $volunteer->where('volunteer_application.stage', '==', 10)
        ->count();

        $adminId = auth()->user()->id;
        $unreadNotificationsCount = Notifications::where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->count();

        $adminNotifications = Notifications::where('receiver_id', $adminId)->orderByDesc('created_at')->take(5)->get();

        return view('admin_contents.volunteers', ['unreadNotificationsCount' => $unreadNotificationsCount,'adminNotifications' =>$adminNotifications, 'volunteer' => $volunteer, 'volunteerCount' => $volunteerCount, 'volunteerPendingCount' => $volunteerPendingCount, 'volunteerApprovedCount' => $volunteerApprovedCount, 'volunteerRejectedCount' => $volunteerRejectedCount]);
    }
    public function UserVolunteerProgress(Request $request, $userId, $applicationId)
    {
        $user = auth()->user();

        $authUser = auth()->user()->id;
        $adminId = User::where('role', 'admin')->value('id');;

        $unreadNotificationsCount = Notifications::where('receiver_id', $authUser)
            ->whereNull('read_at')
            ->count();

        $userNotifications = Notifications::where('receiver_id', $authUser)->orderByDesc('created_at')->take(5)->get();
        
        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application', function ($query) use ($user, $userId, $applicationId) {
            $query->where('user_id', $userId)
                  ->where('application_id', $applicationId); 
        })->first();

        $scheduleInterview = ScheduleInterview::with('schedule', 'application')
    ->join('schedules', 'schedule_interviews.schedule_id', '=', 'schedules.id')
    ->join('application', 'schedule_interviews.application_id', '=', 'application.id')
    ->where('schedule_interviews.application_id', $userVolunteerAnswers->volunteer_application->application_id)
    ->where('application.user_id', $userId)
    ->where('schedules.schedule_status', 'Accepted') // Add this line for the additional condition
    ->select('schedule_interviews.*')
    ->first();
    
        $firstnotification = Notifications::where('receiver_id', $authUser)->where('sender_id', $adminId)->where('application_id', $applicationId)->orderByDesc('created_at')->get();
        // dd($firstnotification);
        // dd($userVolunteerAnswers);
        $stage = $userVolunteerAnswers->volunteer_application->stage;
        $answers = json_decode($userVolunteerAnswers->answers, true);

        return view('user_contents.volunteer_progress', ['firstnotification' => $firstnotification, 'unreadNotificationsCount' => $unreadNotificationsCount, 'userNotifications' => $userNotifications, 'scheduleInterview' => $scheduleInterview,'userVolunteerAnswers' => $userVolunteerAnswers, 'user' => $user->id, 'stage' => $stage, 'answers' => $answers]);
    }
    public function AdminVolunteerProgress(Request $request, $userId, $applicationId)
    {
        //  dd($userId, $applicationId);
        $authUser = auth()->user()->id;
        $adminId = User::where('role', 'admin')->value('id');;

        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application', function ($query) use ( $userId, $applicationId) {
            $query->where('user_id', $userId)
                  ->where('application_id', $applicationId); 
        })->first();
        
        $scheduleInterview = ScheduleInterview::with('schedule', 'application')
        ->join('schedules', 'schedule_interviews.schedule_id', '=', 'schedules.id')
        ->join('application', 'schedule_interviews.application_id', '=', 'application.id')
        ->where('schedule_interviews.application_id', $userVolunteerAnswers->volunteer_application->application_id)
        ->where('application.user_id', $userId) 
        ->select('schedule_interviews.*')
        ->latest()->first();
    
        $acceptedSchedule = ScheduleInterview::with('schedule', 'application')
        ->join('schedules', 'schedule_interviews.schedule_id', '=', 'schedules.id')
        ->join('application', 'schedule_interviews.application_id', '=', 'application.id')
        ->where('schedule_interviews.application_id', $userVolunteerAnswers->volunteer_application->application_id)
        ->where('application.user_id', $userId)
        ->where('schedules.schedule_status', 'Accepted') // Add this line for the additional condition
        ->select('schedule_interviews.*')
        ->first();
        
        // dd($scheduleInterview);
        $stage = $userVolunteerAnswers->volunteer_application->stage;
        $answers = json_decode($userVolunteerAnswers->answers, true);
        $adminId = auth()->user()->id;

        $firstnotification = Notifications::where('receiver_id', $authUser)->where('sender_id', $userId)->where('application_id', $applicationId)->orderByDesc('created_at')->get();

        $unreadNotificationsCount = Notifications::where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->count();

        $adminNotifications = Notifications::where('receiver_id', $adminId)->orderByDesc('created_at')->take(5)->get();

        return view('admin_contents.volunteer_progress', ['unreadNotificationsCount' => $unreadNotificationsCount, 'adminNotifications' => $adminNotifications, 'acceptedSchedule' => $acceptedSchedule, 'userVolunteerAnswers' => $userVolunteerAnswers, 'user' => $userId, 'stage' => $stage, 'answers' => $answers, 'scheduleInterview' => $scheduleInterview, 'firstnotification' => $firstnotification]);
    }
    public function updateVolunteerStage(Request $request, $userId, $applicationId)
    {
        $adminId = auth()->id();
        
        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId, $applicationId) {
            $query->where('id', $userId);
        })->latest()->first();

        if ($userVolunteerAnswers) {
            if ($userVolunteerAnswers->volunteer_application->stage == 0){
                $notificationMessage = 'Your volunteer application has been validated by the shelter. Proceed to next step now.';
            }
            elseif ($userVolunteerAnswers->volunteer_application->stage == 4) {
                $notificationMessage = 'Congratulations! The shelter has accepted your volunteer application. Welcome to Noahs Ark Family!';
            }

    
            $notification = new Notifications();
            $notification->application_id = $applicationId; 
            $notification->sender_id = $adminId;
            $notification->receiver_id = $userId; 
            $notification->concern = 'Volunteer Application';
            $notification->message = $notificationMessage;
            $notification->save();

            $newStage = $userVolunteerAnswers->volunteer_application->stage + 1;

            $userVolunteerAnswers->volunteer_application->update(['stage' => $newStage]);
        }
        return redirect()->back()->with(['volunteer_progress' => true]);
    }
    
    
    public function volunteerReject($userId, $applicationId, Request $request)
    {
        $adminId = auth()->id();
        $reason = $request->input('reason');
        // dd($firstName);
        $notificationMessage = "The shelter has rejected your Volunteer Application. Due to: $reason";

        $notification = new Notifications();
        $notification->application_id = $applicationId; 
        $notification->sender_id = $adminId;
        $notification->receiver_id = $userId; 
        $notification->concern = 'Volunteer Application';
        $notification->message = $notificationMessage;
        $notification->save();

        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->latest()->first();
        // dd($userId, $applicationId);
        if ($userVolunteerAnswers) {
            $volunteerApplication = $userVolunteerAnswers->volunteer_application;
    
            $volunteerApplication->update(['stage' => 10]);
        
            return redirect()->back()->with(['volunteer_progress' => true]);
        } else {
            return redirect()->back()->with('error', 'Volunteer application not found for the specified user.');
        }

        return redirect()->back()->with(['volunteer_progress' => true]);
    }

    public function cancelApplication($userId, $applicationId, Request $request)
    {
        $adminId = User::where('role', 'admin')->value('id');
        $reason = $request->input('reason');
        $notificationMessage = "has cancelled their Volunteer application due to: $reason";

        $notification = new Notifications();
        $notification->application_id = $applicationId; 
        $notification->sender_id = $userId;
        $notification->receiver_id = $adminId; 
        $notification->concern = 'Volunteer Application';
        $notification->message = $notificationMessage;
        $notification->save();

        $volunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->latest()->first();
        
        if ($volunteerAnswers) {
            // Assuming you have a 'stage' column in the 'volunteer_application' table
            $volunteerAnswers->volunteer_application->update(['stage' => 11]);
        
            return redirect()->back()->with(['application_canceled' => true]);
        } else {
            return redirect()->back()->with('error', 'Volunteer application not found for the specified user.');
        }
    }

    public function volunteerInterviewReject($userId, $applicationId, Request $request)
    {
        $adminId = User::where('role', 'admin')->value('id');
        $reason = $request->input('reason');

        $notificationMessage = "The shelter has rejected your Interview Schedule due to: $reason. Please, re-schedule.";

        $notification = new Notifications();
        $notification->application_id = $applicationId; 
        $notification->sender_id = $adminId;
        $notification->receiver_id = $userId; 
        $notification->concern = 'Volunteer Application';
        $notification->message = $notificationMessage;
        $notification->save();

        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->latest()->first();
        
        if ($userVolunteerAnswers) {
            $volunteerApplication = $userVolunteerAnswers->volunteer_application;
 
            // Decrement the stage column by 1
            $volunteerApplication->decrement('stage', 1);

            if ($volunteerApplication) {
                $scheduleInterview = ScheduleInterview::where('application_id', $volunteerApplication->application_id)->first();
                if ($scheduleInterview) {
                    $schedule = $scheduleInterview->schedule;
                    
                    if ($schedule) {
                        $schedule->update(['schedule_status' => 'Rejected']);
                    }
                }   
                return redirect()->back()->with(['volunteer_progress' => true]); 
            }
        
            return redirect()->back()->with(['volunteer_progress' => true]);
        } else {
            return redirect()->back()->with('error', 'Volunteer application not found for the specified user.');
        }
    }
    public function cancelInterview($userId, $applicationId, Request $request)
    {
        $adminId = User::where('role', 'admin')->value('id');
        $reason = $request->input('reason');
        $notificationMessage = "has cancelled the Interview Schedule due to $reason";

        $notification = new Notifications();
        $notification->application_id = $applicationId; 
        $notification->sender_id = $userId;
        $notification->receiver_id = $adminId; 
        $notification->concern = 'Volunteer Application';
        $notification->message = $notificationMessage;
        $notification->save();

        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->latest()->first();
        
        if ($userVolunteerAnswers) {
            $volunteerApplication = $userVolunteerAnswers->volunteer_application;
 
            // Decrement the stage column by 1
            $volunteerApplication->decrement('stage', 2);

            if ($volunteerApplication) {
                $scheduleInterview = ScheduleInterview::where('application_id', $volunteerApplication->application_id)->latest()->first();
                if ($scheduleInterview) {
                    $schedule = $scheduleInterview->schedule;
                    
                    if ($schedule) {
                        $schedule->update(['schedule_status' => 'Canceled']);
                    }
                }   
                return redirect()->back()->with(['send_schedule' => true]); 
            }
        
            return redirect()->back()->with(['send_schedule' => true]);
        } else {
            return redirect()->back()->with('error', 'Volunteer application not found for the specified user.');
        }
    }
    public function adminCancelInterview($userId, $applicationId, Request $request)
    {
        $adminId = User::where('role', 'admin')->value('id');
        $reason = $request->input('reason');
        $notificationMessage = "The shelter has cancelled the interview schedule due to: $reason. Please, re-schedule";

        $notification = new Notifications();
        $notification->application_id = $applicationId; 
        $notification->sender_id = $adminId;
        $notification->receiver_id = $userId; 
        $notification->concern = 'Volunteer Application';
        $notification->message = $notificationMessage;
        $notification->save();

        $userVolunteerAnswers = VolunteerAnswers::whereHas('volunteer_application.application.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->latest()->first();
        
        if ($userVolunteerAnswers) {
            $volunteerApplication = $userVolunteerAnswers->volunteer_application;
 
            // Decrement the stage column by 1
            $volunteerApplication->decrement('stage', 2);

            if ($volunteerApplication) {
                $scheduleInterview = ScheduleInterview::where('application_id', $volunteerApplication->application_id)->latest()->first();
                if ($scheduleInterview) {
                    $schedule = $scheduleInterview->schedule;
                    
                    if ($schedule) {
                        $schedule->update(['schedule_status' => 'Canceled']);
                    }
                }   
                return redirect()->back()->with(['volunteer_progress' => true]); 
            }
        
            return redirect()->back()->with(['volunteer_progress' => true]);
        } else {
            return redirect()->back()->with('error', 'Volunteer application not found for the specified user.');
        }
    }
    public function volunteer_form() {
        $authUser = auth()->user()->id;

        $unreadNotificationsCount = Notifications::where('receiver_id', $authUser)
            ->whereNull('read_at')
            ->count();

        $userNotifications = Notifications::where('receiver_id', $authUser)->orderByDesc('created_at')->take(5)->get();

        return view('user_contents.volunteerform', ['unreadNotificationsCount' => $unreadNotificationsCount, 'userNotifications' => $userNotifications]);
    }
    public function export_volunteer()
    {
        return Excel::download(new VolunteersExport, 'Volunteers.xlsx');
    }
}
