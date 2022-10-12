I regret to inform you that your application for a non-payable grant under the Youth Educational Assistance Fund (YEAF) received on {{ date('F d, Y', strtotime($grant->application_receive_date)) }}, has been denied for the following reason(s):
<br/><br/>
<ul><li>{{ $grant->custom_denial_reason }}</li></ul>
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
