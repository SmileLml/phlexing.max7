<html lang="zh-cmn-Hans">
  <head>
    <script>
        location.hash = '#/chart/home/' + parent.window.screen.id;
        window.scopeList  = parent.window.scopeList;

        window.saveAsDraft           = parent.window.saveAsDraft;
        window.saveAsPublish         = parent.window.saveAsPublish;
        window.backBrowse            = parent.window.backBrowse;
        window.fullscreen            = parent.window.fullScreen;
        window.selectOptionApi       = parent.window.createLink('screen', 'ajaxGetOptions');
        window.fetchChartApi         = parent.window.fetchChartApi;
        window.fetchDataApi          = parent.window.fetchDataApi;
        window.fetchMetricDataApi    = parent.window.fetchMetricDataApi;
        window.fetchFilterOptionsApi = parent.window.fetchFilterOptionsApi;
    </script>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0"
    />
    <link rel="icon" href="./favicon.ico" />

    <script type="module" crossorigin src="<?php echo $this->app->getWebRoot();?>static/js/index.js"></script>
    <style>
    .n-layout-scroll-container {height: unset !important;}
    </style>
  </head>
  <body>
    <div id="appProvider" style="display: none;"></div>
    <div id="app">
      <div class="first-loading-wrp">
        <div class="loading-wrp">
          <span class="dot dot-spin"><i></i><i></i><i></i><i></i></span>
        </div>
      </div>
    </div>
  </body>
</html>

