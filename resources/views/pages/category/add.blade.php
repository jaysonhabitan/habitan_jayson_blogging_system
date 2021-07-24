<x-layouts.master>
  <x-header-banner />

  <div class="divide-y-2 divide-y-white">
    <div class="grid p-8 px-16 pb-0 mx-auto grid-cols-3 container">
      <div class="text-3xl font-bold align-middle">
        Category Management
      </div>

        <a href={{ route('categories.index') }} class="mx-auto uppercase casebg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
          Go back to category list
        </a>
    </div>

    <div class="mx-auto container px-16 mt-5">
      <div class="mt-10">
        @if ($category) 
          <category-form-component 
            route-name="{{ Route::currentRouteName() }}" 
            :category="{{ $category }}"
          />
        @else 
          <category-form-component 
            route-name="{{ Route::currentRouteName() }}" 
          />
        @endif
      </div>
    </div>

  </div>
</x-layouts.master>