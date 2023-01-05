@props(['url'])
<tr>
<td class="header">
<a href="https://brain-cloud.uk" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://api.brain-cloud.uk/Logo.png" class="logo">
@endif
</a>
</td>
</tr>

<style>
    .between-x {
        font-size: 72px;
        padding-left: 20px;
        padding-right: 20px;
        margin: 0;
        font-weight: lighter;
    }
</style>
