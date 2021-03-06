<x-layouts.master>
  <x-header-banner />

  <div class="divide-y-2 divide-y-white">
    <div class="grid p-8 px-16 pb-0 mx-auto grid-cols-3 container">
        <div class="text-3xl font-bold align-middle">
          Category Management
        </div>

        <div class="flex col-span-2">
          @if (Auth::user()->can('create-category'))
            <a href={{ route('categories.create') }} class="bg-transparent mr-2 hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded">
              Create new
            </a>
          @endif

          <search-component 
            search-by="name"
            search-query="{{ $searchQuery }}" 
            go-to-route="manages/categories"
          />
        </div>
    </div>
    
    <div class="mx-auto container px-16 mt-5">
      <div class="mt-0">
        <div class="overflow-x-auto">
          <div class="h-full flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
              <div class="w-full lg:w-5/6">
                  <div class="bg-white shadow-md rounded my-6">
                      <table class="min-w-max w-full table-auto">
                          <thead>
                              <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                  <th class="py-3 px-6 text-left">Name</th>
                                  <th class="py-3 px-6 text-left">Description</th>
                                  <th class="py-3 px-6 text-center">Slug</th>
                                  <th class="py-3 px-6 text-center">Is Visible?</th>
                                  <th class="py-3 px-6 text-center">Actions</th>
                              </tr>
                          </thead>
                          <tbody class="text-gray-600 text-sm font-light">
                            @foreach($categories as $key => $category)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                              <td class="py-3 px-6 text-left whitespace-nowrap">
                                  <div class="flex items-center">
                                      <span class="font-medium">{{ $category->name }}</span>
                                  </div>
                              </td>
                              <td class="py-3 px-6 text-left">
                                  <div class="flex items-center">
                                      <span>{{ $category->description }}</span>
                                  </div>
                              </td>
                              <td class="py-3 px-6 text-center">
                                  <div class="items-center">
                                      <span>{{ $category->slug }}</span>
                                  </div>
                              </td>
                              <td class="py-3 px-6 text-center">
                                <div class="flex">
                                  <span class="mx-auto">
                                    @if ($category->is_visible)
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                      </svg>
                                    @else 
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                      </svg>
                                    @endif
                                  </span>
                                </div>
                              </td>
                              <td class="py-3 px-6 text-center">
                                  <div class="flex item-center justify-center">
                                    @if (Auth::user()->can('update-category'))
                                      <div class="cursor-pointer w-4 mr-2 transform hover:text-yellow-400 hover:scale-110">
                                        <a href="{{ route('categories.show', $category->id) }}">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                          </svg>
                                        </a>
                                      </div>
                                    @endif

                                    @if (Auth::user()->can('update-category'))
                                      <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                          </button>
                                        </form>
                                      </div>
                                    @endif
                                    </div>
                                  </td>
                              </tr>
                            @endforeach
                          </tbody>
                      </table>
                  </div>
                  <span class="mt-2">
                    {{ $categories->links() }}
                  </span>
              </div>
          </div>
      </div>
      </div>
    </div>

  </div>
</x-layouts.master>