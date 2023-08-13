<div class="flex-row center gap-12 notification padding-4">
    {{ $notification->data['userName'] }} has joined {{ $notification->data['syndicateName'] }} <form action="/user/notification/delete" method="POST">@csrf<input type="hidden" name="id" value="{{ $notification->id }}"/><button class="button" type="submit"><i class="fa-solid fa-trash-can"></i></button></form>
</div>