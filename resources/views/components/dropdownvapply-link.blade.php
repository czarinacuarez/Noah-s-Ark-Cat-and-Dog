<!-- x-dropdownvapply-link.blade.php -->

@props(['href', 'imageSource', 'name', 'currentDate', 'markAsRead', 'notificationId'])

<a onclick="handleLinkClick('{{ $notificationId }}')" href="{{ $href }}"
    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
    <div class="flex items-center">

        <div class="flex items-center relative">
            <div
                class="absolute bottom-0 right-0 bg-cyan-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs pointer-events-none">
                <div class="flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3">
                        <path
                            d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                    </svg>

                </div>

            </div>

            <!-- Profile image -->
            <img src="{{ $imageSource ?? asset('default_image_url.jpg') }}" alt="Profile Image"
                class="w-10 h-10 rounded-full mr-2">
        </div>

        <div class="flex-1">
            <div class="flex-1 ps-1">
                <span class="font-bold">{{ $name }}</span>
                {{ $slot }}
            </div>
            <p class="text-xs p-1">{{ $currentDate }}</p>
        </div>

        @if($markAsRead == NULL || $markAsRead == "")
            <div class="w-2 h-2 bg-red-500 rounded-full ml-2"></div> 
        @else
            
        @endif
    </div>
</a>
<script>
    function handleLinkClick(notificationId) {
        fetch("{{ route('update.mark_as_read', ['notificationId' => ':notificationId']) }}".replace(':notificationId', notificationId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({
            }),
        })
        .then(response => {
            if (response.ok) {
                console.log('Mark as read successfully updated');
            } else {
                console.error('Failed to update mark as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
