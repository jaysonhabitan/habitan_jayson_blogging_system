<x-layouts.master>
  <x-header-banner />

  <div class="divide-y-2 divide-y-white">
    <div class="grid p-8 px-16 pb-0 mx-auto grid-cols-3 container">
      <div class="text-3xl font-bold align-middle">
        User Management
      </div>

        <a href={{ route('users.index') }} class="mx-auto uppercase casebg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
          Go back to users list
        </a>
    </div>

    <div class="mx-auto container px-16 mt-5">
      <div class="mt-10">
        @if (isset($user)) 
          <user-form-component 
            :roles="{{ $roles }}"
            :user="{{ $user }}"
            :admin="{{ $admin }}"
            :permissions="{{ $permissions }}"
            :role-permissions="{{ json_encode($rolePermissions) }}"

          />
        @else 
          <user-form-component
            :roles="{{ $roles }}"
            :admin="{{ $admin }}"
            :permissions="{{ $permissions }}"
            :role-permissions="{{ json_encode($rolePermissions ) }}"
          />
        @endif
      </div>
    </div>

  </div>
</x-layouts.master>