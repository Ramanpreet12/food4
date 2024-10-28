<h1>Welcome delivery boy</h1>
<li>
    <!-- Authentication -->
<form method="POST" action="{{ route('delivery.logout') }}">
@csrf

<x-responsive-nav-link :href="route('delivery.logout')"
onclick="event.preventDefault();
   this.closest('form').submit();">
{{ __('Log Out') }}
</x-responsive-nav-link>
</form>
</li>