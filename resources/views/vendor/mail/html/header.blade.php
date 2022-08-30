<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://cordum.vercel.app/android-chrome-512x512.png" class="logo" alt="Cordum Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
