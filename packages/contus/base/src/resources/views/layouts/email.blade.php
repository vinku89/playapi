<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body data-gr-c-s-loaded="true" cz-shortcut-listen="true" style="width: 600px;margin: 3% auto;background: #EFEFEF;">
        <table width="100%" cellpadding="0" cellspacing="0" style="font-family: arial, sans-serif;font-size: 14px;color: #31353b;background: #fff;">
            <tbody>
                <tr>
                    <td colspan="2" style="padding: 0;line-height: 0px;border: 2px solid #27caf0;"></td>
                </tr>
                <tr>
                    <td style="padding: 20px;">
                        <img src="{{$getBaseAssetsUrl('images/email/logo.png')}}">
                    </td>
                    <td style="text-align: right;padding: 20px;color: #7c7c7c;">Connect with us
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.facebook_url' )}}" style="display: inline-block; vertical-align: middle;margin-left: 7px;">
                            <img src="{{$getBaseAssetsUrl('images/email/fb.png')}}">
                        </a>
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.twitter_url' )}}" style="display: inline-block;vertical-align: middle;">
                            <img src="{{$getBaseAssetsUrl('images/email/twitter.png')}}">
                        </a>
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.googleplus_url' )}}" style="display: inline-block; vertical-align: middle;">
                            <img src="{{$getBaseAssetsUrl('images/email/g+.png')}}">
                        </a>
                    </td>
                </tr>                    
                <tr>
                    <td style="padding: 5% 20px 0;font-size: 15px;" colspan="2">
                        {!!$content!!}                            
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px;">
                        <p style="color: #27caf0;margin: 0 0 5px;font-weight: bold;">Regards,</p>
                        <p style="color: #333;margin: 0;font-weight: bold;">{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}} Team</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 20px;background: #e5e5e5;font-size: 16px;border-top: 1px solid #e5e5e5;" colspan="2">
                        <div style="margin: auto;text-align: center;font-size: 15px;">
                            <p style=" margin: 0 0 9px; color: #000; font-size: 15px; font-weight: bold;">Vplayed App on Mobile</p>
                            <p style="margin: 0 0 15px;line-height: 150%;font-size: 12px;color: #4d4d4d;">Access your videos anywhere,anytime by a single tap in the mobile</p>
                            <a href="{{config ()->get ( 'settings.general-settings.site-settings.apple_appstore_url' )}}" style=" display: inline-block; margin: 0 3px;">
                                <img src="{{$getBaseAssetsUrl('images/email/apple.png')}}">
                            </a>
                            <a href="{{config ()->get ( 'settings.general-settings.site-settings.google_playstore_url' )}}" style="display: inline-block;">
                                <img src="{{$getBaseAssetsUrl('images/email/google.png')}}">
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
