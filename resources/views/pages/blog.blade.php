<x-layouts.master >
    <x-header-banner />

    <div class="w-full min-h-screen px-10 pt-6">
        <div class="divide-y-2">
            <div class="divide-x-2 flex">
                <div>
                    <a href="/blogs" class="text-4xl text-gray-700 w-full mr-4">
                        All Blog Articles
                    </a>
                </div>

                <div v-if="{{ !empty($selectedCategory) }}">
                    <span class="text-4xl border-none pl-4">
                        {{ ucfirst($selectedCategory) }}
                    </span>
                </div>
            </div>

            <div class="mx-auto w-full mt-4">
                <div class="p-10 grid grid-cols-6 divide-x-2">
                    <div class="text-right">
                        <span class="text-3xl text-center text-gray-700  mr-5">
                            Category List
                        </span>

                        <div class="pa-5 mt-5 mr-5">
                            @foreach($categories as $category)
                                <div class="text-gray-500 text-xl mb-3">
                                    <a href="/blogs/categories/{{ $category->slug }}" class="w-full hover:underline hover:text-gray-700">
                                        {{ $category->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-span-5">
                        <div class="text-gray-700 
                            mx-auto 
                            flex 
                            flex-row 
                            ml-4 
                            {{ $routeName === 'me' ? 'justify-between mr-12' : '' }}"
                        >
                            <div class="text-3xl ml-16">
                                {{ $routeName === 'me' ? 'My' : '' }} Articles
                            </div>
                            
                            @if ($routeName !== 'me')
                                <div class="mx-auto">
                                    <search-component 
                                        search-by="title"
                                        search-query="{{ $searchQuery }}" 
                                        go-to-route="blogs" 
                                    />
                                </div>
                            @endif

                            <div class="{{ $routeName == 'me' ? '' : 'mr-12' }}">
                                @if (Auth::user() && Auth::user()->can('create-post'))
                                    <compose-article-component :categories="{{ $categories }}"/>
                                @endif
                            </div>
                        </div>

                        @if (count($posts) > 0) 
                            @foreach($posts as $post)
                                <div class="w-full p-5 pb-1 px-20">
                                    @if (Auth::user())
                                        <article-card-component 
                                            :user="{{ Auth::user()->load('permissions') }}"
                                            :post="{{ $post }}" 
                                            :comments="{{ $post->comments }}"
                                            :categories="{{ $categories }}"
                                            :is-from-blog-page="{{ json_encode($routeName !== 'me') }}"
                                        />
                                    @else 
                                        <article-card-component 
                                            :post="{{ $post }}" 
                                            :comments="{{ $post->comments }}"
                                            :categories="{{ $categories }}"
                                        />
                                    @endif
                                </div>
                            @endforeach
                        @else 
                            <div class="uppercase w-full p-5 mt-32 px-20 mx-auto text-center align-center text-gray-300 text-6xl">
                                No article(s) found
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer-banner />
</x-layouts.master>