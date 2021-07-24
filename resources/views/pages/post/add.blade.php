<x-layouts.master>
  <x-header-banner />

  <div class="divide-y-2 divide-y-white">
    <div class="grid p-8 px-16 pb-0 mx-auto grid-cols-3 container">
      <div class="text-3xl font-bold align-middle">
        Post Management
      </div>

        <a href={{ route('posts.index') }} class="mx-auto uppercase casebg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
          Go back to posts list
        </a>
    </div>

    <div class="mx-auto container px-16 mt-5">
      <div class="mt-10">
        @if (isset($post)) 
          <post-form-component 
            :categories="{{ $categories }}"
            :post="{{ $post }}"
          />
        @else 
          <post-form-component 
            :categories="{{ $categories }}"
          />
        @endif
      </div>
    </div>

  </div>
</x-layouts.master>