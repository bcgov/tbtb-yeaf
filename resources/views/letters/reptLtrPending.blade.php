Our office is currently in receipt of your application for the Youth Educational Assistance Fund.  Your application is being held pending receipt of the information as indicated below.
<br/><br/>
<ul><li>{{ $grant->custom_pending_reason }}</li></ul>
<br/><br/>
The Youth Education Assistance Fund program runs from August 1 to July 31 of each year.  Applications for the new program year will be available in July of each year.  You may download all required documentation on our web site at www.StudentAidBC.ca
<br/><br/>
If you have any questions, please contact me at
@if(is_null($officer))
    {{ $user->tele }}.
@else
    {{ $officer->tele }}.
@endif
<br/>
