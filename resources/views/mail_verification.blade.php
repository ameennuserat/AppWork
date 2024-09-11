{{-- @component('mail::message')
<h1>hello {{$name}}</h1>
<h1>We have received your request to enter the application</h1>
<p>You can use the following link to confirm your account:</p>

@component('mail::panel')
<a href="{{$link}}">{{$link}}</a>
@endcomponent

<p>The allowed duration of the code is one hour from the time the message was sent</p>
@endcomponent --}}


<H1>hello{{$name}}</H1>
<p>this your link to {{$link}}</p>
