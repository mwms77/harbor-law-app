@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; color: #ffffff; text-decoration: none; font-size: 20px; font-weight: bold;">
{{ trim($slot) === 'Laravel' ? 'Laravel' : $slot }}
</a>
</td>
</tr>
