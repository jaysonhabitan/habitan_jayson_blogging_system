<div class="bg-white py-4 {{ $actionBar ?? '' }}">
    <div class="container mx-auto flex justify-between items-center md:pl-0 text-lg font-semibold text-gray-100 ">
        <div class="text-gray-600 font-bold flex">
            <div class="flex space-x-4">
                
                <a class=" hover:text-gray-600 cursor-pointer" href="/">
                    Home
                </a>
                <a class=" hover:text-gray-600 cursor-pointer" href="/blogs">
                    Blogs
                </a>
            </div>
            
        </div>
        <div class="flex space-x-4 text-gray-600">
            @if(!Auth::user())
                <div>
                    <a class=" hover:text-gray-600 cursor-pointer" href="{{ route('login') }}">
                        Login
                    </a>
                </div>
                <div>
                    <a  href="{{ route('register') }}" class=" hover:text-gray-600 cursor-pointer" href="/">
                        Sign-up
                    </a>
                </div>
            @endif

            @if(Auth::user())
                @if(!Auth::user()->isContributor())
                    <template>
                        <x-headers.admin-options />
                    </template>
                @endif
                <template>
                    <menu-options-component 
                        title="Hi {{ Auth::user()->name }}!" 
                    />
                </template>
            @endif
        </div>
    </div>
</div>