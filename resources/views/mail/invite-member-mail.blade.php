@component('mail::message')
# Introduction

Hi,
We are Inviting you to join our Chat Support Community.

@component('mail::button', ['url' => route('verify.email',['email'=>$data->email,'token'=>$data->token])])
Click to Join
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
