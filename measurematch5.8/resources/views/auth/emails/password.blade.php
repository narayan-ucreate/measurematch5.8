
<?php
    $name=explode(' ',$user['name']);
    $user_name=$name[0];
    $userName = $user['email'];
?>

@extends('emails.layout.email',['userEmail'=>$userName])
@section('content')

<table width="100%" style="max-width:800px; background:#E5F1E8;" align="center">

  <tr>
      <td align="left" style="padding:20px 50px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
          <p style="padding:0px 50px; margin-bottom:15px;">Hi {{ucwords(trim($user_name." ".$user['last_name']))}},</p>

           <?php if($user['user_type_id']==1){
              $user_type='Expert';
            }else{
              $user_type='Client';
            }
            $clienEmail = getenv('CLIENT_EMAIL');
            $mailTo = $clienEmail;
          ?>

          <p style="padding:0px 50px; margin-bottom:15px;">

    You recently requested to reset your password for your <strong style="color:#252161;">MeasureMatch {{$user_type}}</strong> account. Please click the link below to reset it.
    </p>

     <p style="padding:0px 50px; margin-bottom:15px;text-align: center;">

      <a href="{{ $link = url('password/reset', $token,$ssl).'?email='.urlencode($user->getEmailForPasswordReset()) }}" style="font-family: Arial,Helvetica,sans-serif; background: rgb(30, 112, 183) none repeat scroll 0% 0%; border-radius: 2px; font-size: 12px; text-decoration: none; color: rgb(255, 255, 255); font-weight: bold; text-align:center; margin: 0px auto; display: block; padding: 7px 0px; text-align:center; width: 160px;"> Reset your password </a>
      </p>


    <p style="padding:0px 50px; margin-bottom:15px;">
      If you did not request a password reset, please ignore this message or contact us at {{ $mailTo }} with any questions.
    </p>

    <p style="padding:0px 50px; margin-bottom:15px;">
      If you’re having trouble with the button above, copy and paste the link below into your web browser.
    </p>

    <p style="font-family: Arial,Helvetica,sans-serif; margin-bottom: 20px; padding:0px 50px; text-decoration: none; color: rgb(37, 33, 97); font-size: 14px;word-wrap: break-word;word-break: break-all;white-space: inherit;">
    <a style="word-wrap: break-word;word-break: break-all;white-space: inherit;" href="{{ $link = url('password/reset', $token,$ssl) }}"> {{ $link = url('password/reset', $token,$ssl) }} </a>
    </p>


    <p style="font-family: arial; font-size: 14px; text-align:left;padding:0px 50px;">Thank You,<br/><strong style="color:#252161;">The MeasureMatch Team </strong></p>

  </td>
  </tr>
</table>

@stop
