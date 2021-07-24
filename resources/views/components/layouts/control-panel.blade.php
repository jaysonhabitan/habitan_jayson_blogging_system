<x-layouts.master>
  <side-nav-component/>

  <x-slot name="pageScript">
      <script>
      window.BLOG = {
          PAGE: 'ControlPanelPage',
      };

      </script>
  </x-slot>
</x-layouts.master>