<div class="padding-8 flex-column gap-12 center-x border-2">
    <h2 class="title-small">Notifications:</h2>
    @foreach(auth()->user()->notifications as $notification)
        @php $notification_comp = 'partials.notification.'.Str::snake(class_basename($notification->type), '_'); @endphp
        <x-dynamic-component :component="$notification_comp" :notification="$notification" />
    @endforeach
</div>