<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%; margin:0 auto; padding:0; width:100%" height="100%"
      width="100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>iSaudavel.com</title>
</head>
<body width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%">

<div class="hover-container" width="100%" style="height:100%; margin:0; padding:0; width:100%" height="100%"
>
   <span class="hover-edit-container">
   <i class="zmdi zmdi-edit"></i>
   </span>
    <table width="100%" border="0" cellspacing="0" cellpadding="20" background=""
           style="background-color: #88C657; color: rgb(85, 85, 85); padding-bottom: 50px;">
        <tbody>
        <tr>
            <td>
                <center>
                    <p><br></p>

                    <!-- INIT MAIN -->
                    <table width="440px" cellspacing="0" border="0" align="center">
                        <tbody>

                        <!-- HEADER LOGO WE PLANNER -->
                        <tr>
                            <td style="padding: 50px 50px 20px 50px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85); "
                                align="center">
                                <img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png"
                                     width="200" alt="Logo" border="0" style="-ms-interpolation-mode:bicubic">
                            </td>
                        </tr>

                        <?php
                            if(!isset($data['align']) && empty($data['align'])){
                                $data['align'] = 'center';
                            }
                        ?>

                        <!-- TITULO -->
                        @if(isset($data['messageTitle']) && !empty($data['messageTitle']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <span style="font-size: 16px;">
                                    {!! $data['messageTitle'] !!}
                                </span>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE TWO -->
                        @if(isset($data['messageOne']) && !empty($data['messageOne']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageOne'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['messageTwo']) && !empty($data['messageTwo']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageTwo'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['messageThree']) && !empty($data['messageThree']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; ">
                                    <span style="font-size: 14px;">
                                        {!! $data['messageThree'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif

                        <!-- MESSAGE THREE -->
                        @if(isset($data['access_code']) && !empty($data['access_code']))
                        <tr>
                            <td style="padding: 10px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; ">
                                    <span style="font-size: 22px;">
                                        {!!$data['access_code'] !!}
                                    </span>
                                </p>
                            </td>
                        </tr>
                        @endif
                        
                        <!-- BOTÃO -->

                        @if(isset($data['button_link']) && !empty($data['button_link']))
                        <tr>
                            <td style="padding: 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                                align="center">
                                <center>
                                    <a style="transition: all 100ms ease-in; display: block; font-weight: bold; text-align: {{ $data['align'] }}; margin: 10px 10px 10px; text-decoration: none; max-width: 80% !important; background-color: rgb(255, 255, 255);"
                                       class="button-a" align="center"
                                       href="{{$data['button_link']}}">
                                        <span style="color: rgb(255, 255, 255); border-color: #2389EA; background-color: #2389EA; width: 200px; height: 70px; border-radius: 5px; border-width: 5px; font-size: 20px; padding: 15px 30px 15px 30px; margin: 30px 10px;">
                                            {{{$data['button_name']}}}
                                        </span>
                                    </a>
                                </center>
                            </td>
                        </tr>
                        @endif

                        <!-- ULTIMO BLOCO -->
                        @if(isset($data['messageFour']) && !empty($data['messageFour']))
                        <tr>
                            <td style="padding: 20px 20px 40px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; "><span style="font-size: 14px;">
                                    {!! $data['messageFour'] !!}
                                </span>
                                </p>

                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td style="padding: 20px 20px 0px 20px; text-align: {{ $data['align'] }}; max-width: 80% !important; background-color: rgb(255, 255, 255); color: rgb(85, 85, 85);"
                                align="center">
                                <p style="text-align: {{ $data['align'] }}; "><span style="font-size: 14px;">
                                </span>
                                </p>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- INIT FOOTER WEPLANNER -->
                    <table 
                        cellspacing="0" 
                        cellpadding="0" 
                        border="0" 
                        align="center" 
                        width="440px"
                        style="mso-table-lspace:0; mso-table-rspace:0; border-collapse:collapse; border-spacing:0; margin:0 auto; table-layout:fixed; max-width:100%; background-color: #EDECEC"
                        role="presentation">
                        <tbody>
                            <tr>
                                <td style="mso-table-lspace:0; mso-table-rspace:0; color:#888; font-family:sans-serif; line-height:18px; mso-height-rule:exactly; padding:30px 20px 30px 20px; text-align:center; width:100%; color: #2B343A; "
                                    align="center" width="100%">
                                    <hr>
                                    <span style="font-size: 12px; font-style: italic; font-weight: 80;">
                                        <p>
                                            © 2017 <a href="https://isaudavel.com" target="_blank">iSaudavel.com - sua saúde em boas mãos!</a>
                                        </p>
                                        <P>
                                        <!-- texto de informações -->
                                            We Planner Soluções Técnológicas
                                        </p>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- END FOOTER WEPLANNER -->
                </center>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>