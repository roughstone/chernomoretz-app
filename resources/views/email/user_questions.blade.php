@component('mail::message')
От {{ $data->email }}. <br>
<br>
{{ $data->names }} написа,<br>
<br>
{{ $data->text }}
@endcomponent
