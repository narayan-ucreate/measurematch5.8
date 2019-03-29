<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
        <title>Contract</title><link rel="stylesheet" href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}"><link rel="stylesheet" href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}">
        <style>
        .container-pdf{ width:1024px; margin:0 auto; float:none}        
        .full-width{ width:100%; float:left;}
        .pull-left{ float:left;}
        .topright-section span{ width:100%; float:left; margin-bottom:10px;}
        .topleft-section span{ width:100%; float:left; margin-bottom:10px;}
        .registered-org{width:100%; float:left; margin-bottom:10px;}
        .topleft-section .pdf-logo{ margin:20px 0; float:left;}
        .container-pdf .company-name{ font-size:18px;}
        .topright-section{ margin-top:10px;}
        .pdf-header-section{ width:100%; float:left; border-left: 0; border-top: 0; border-right: 0; border-bottom:1px solid #e0e0e0; padding-bottom:20px; margin-bottom:30px; padding-left: 30px; padding-right: 30px;}
        .pdf-content-section{ width:100%; float:left;padding-left: 30px; padding-right: 30px;}
        .pdf-content-section h2{ font-size:18px; margin:0;}
        .pdf-content-section h4{ font-size:19px; margin:0 0 15px 0; font-weight:normal}        
        .deliverables-section p{ width:100%; float:left;}
        .attachment-section{ width:100%; float:left}                
        .contract-value-section{ width:100%; float:left; background: #FAFAFA; border: 1px solid #e0e0e0; border-radius: 2px 2px 0 0; margin-top:15px; padding:20px 15px; margin-bottom:25px;}
        .value-section{ width:100%; float:left; background: #FAFAFA;border: 1px solid #e0e0e0;border-radius: 2px 2px 0 0; margin-bottom:20px; padding:0px;}
        </style>
    </head>

<body>
    @yield('content')
</body>
</html>