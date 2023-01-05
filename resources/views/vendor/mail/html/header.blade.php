@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://api.brain-cloud.uk/Logo.png" class="logo">
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@endif
</a>
</td>
</tr>
