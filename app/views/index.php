<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="css/favicon.ico" rel="icon" type="image/x-icon" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/typicons/font/typicons.min.css">
        <link rel="stylesheet" href="assets/css/gibson.css">
        <link rel="stylesheet" href="assets/css/app.css">
        <title>Radarzone API</title>
    </head>
    <body>
        <h1><span class="typcn typcn-wi-fi"></span> Radarzone </h1>
        <span>API REST Service</span>

        <?php if(count(\Bootie\App::$routes)):?>
            <table class="table table-striped">
                <tr>
                    <th>Method</th>
                    <th>Route</th>
                    <th>Controller</th>
                    <th>Before</th>
                    <th>After</th>
                </tr>
            <?php $c=0; foreach(\Bootie\App::$routes as $url => $route):?>
                <?php if($url!='/'):$c++;?>
                <tr>
                    <td><?php echo $route->request_method?:'GET';?></td>
                	<td><a href="<?php echo $route->uri;?>"><?php echo $route->uri;?></a></td>
                    <td><?php echo str_replace('Controller','',$route->class);?>@<?php echo $route->method;?></td>
                    <td>
                <?php if($route->before):?>
                    <?php print $route->before;?>
                <?php endif;?>
                    </td>
                    <td>
                <?php if($route->after):?>
                    <?php print $route->after;?>
                <?php endif;?>
                    </td>
                </tr>
                <?php endif;?>
            <?php endforeach;?>
            </table>
            <span>Routes total: <?php echo $c;?></span>
        <?php endif;?>
    </body>
</html>