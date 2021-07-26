<x-layouts.master>
    <x-header-banner >
        <x-slot name="actionBar">floating-action-bar</x-slot>
    </x-header-banner>

    <div class="background-image grid grid-cols-1 m-auto">
        <div class="flex text-gray-100 pt-10">
            <div class="m-auto pt-4 pb-16 sm:m-auto w-3/5 block text-center">
                <h1 class="sm:text-white text-5xl uppercase font-bold text-shadow-md pb-14">
                    Do you love writing stories?
                </h1>
                @if (Auth::check()) 
                    <a href="{{ route('pages.blog')}}" class="text-center bg-gray-50 text-gray-500 hover:text-gray-700 hover:bg-gray-100 py-2 px-4 font-bold text-xl uppercase rounded-full">
                        Compose now
                    </a>
                @else
                    <a href="{{ route('register') }}" class="text-center bg-gray-50 text-gray-500 hover:text-gray-700 hover:bg-gray-100 py-2 px-4 font-bold text-xl uppercase rounded-full">
                        Sign up now 
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-between">
        <div class="w-2/5 md:p-10">
            <img class="rounded-md" src="https://cdn.pixabay.com/photo/2015/07/02/10/40/writing-828911_960_720.jpg" alt=""/>
        </div>

        <div class="w-3/5 p-20 pt-10 pb-10">
            @if ($post)
                <div class="title text-xl font-bold mb-3 text-gray-600">
                    {{ $post->title ?? '' }} <span class="italic font-extrabold text-sm">by {{ $post->author->name ?? '' }}</span>
                </div>

                <div class="desciprtion text-gray-600 m-h-72 overflow-scroll overscroll-contain">
                    {{ $post->body ?? '' }}
                </div>
            @endif

            <div class="text-gray-600 mt-5 flex justify-end">
                <a href="/blogs" class="hover:text-gray-600 text-gray-400 text-l font-bold bg-red uppercase">
                    Read more
                </a>
            </div>
        </div>
       
    </div>

    <x-footer-banner />

    <x-slot name="pageScript">
        <script>
        window.BLOG = {
            PAGE: 'HomePage',
        };

        </script>
    </x-slot>
</x-layouts.master>
