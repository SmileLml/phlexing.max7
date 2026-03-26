Vue.component("v-chart", VueECharts);
var mouseXY = {"x": null, "y": null};
var dragPos = {"x": null, "y": null, "w": 6, "h": 10, "i": {id: "", data: {}}};
let modal;

function saveReport(chartID)
{
    let title = $('#reportTitle').val();
    let desc  = $('#reportDesc').val();
    $.post(createLink('dashboard', 'ajaxSetInfo', 'dashboardID=' + app.dashboard.id), {values: app.filterValues, title: title, desc: desc}, function(data) {
        app.setInfo(chartID, title, desc);
    })
}

var app = new Vue({
    el: '#main',
    data: {
        dashboard: {},
        layout: [],
        draggable: false,
        resizable: false,
        fieldMap: {},
        filters: [],
        filterValues: {},
        index: 0,
        settingVisible: false,
        sysOptions: sysOptions,
    },
    mounted() {
        document.addEventListener("dragover", function (e) {
            mouseXY.x = e.clientX;
            mouseXY.y = e.clientY;
        }, false);
        this.drawCharts();
        var that = this;
        that.autoLayout();
    },
    created() {
        this.initDashboard();
        this.loadFilterMap();
    },
    watch: {
    },
    methods: {
        drawCharts() {
            var that = this;
            setTimeout(function() {
                for(var item of that.layout)
                {
                    var chart = item.i;
                    that.draw(chart);
                }
            }, 500)
        },
        initDashboard() {
            this.dashboard = dashboard;
            for(let index in this.dashboard.layout)
            {
                if(this.dashboard.layout[index].i.type !== 'table') {
                    continue;
                }

                var data = this.dashboard.layout[index].i.data;
                for(var col of data.columns)
                {
                    if(typeof data.rowspan[col.dataIndex] === 'undefined') continue;

                    (function(col) {
                        var index = col.dataIndex;
                        col.customRender = function(text, row, rowIndex) {
                            if(typeof data.rowspan[index][rowIndex] !== 'undefined') {
                                return {children: text, attrs: {rowSpan: data.rowspan[index][rowIndex]}};
                            }
                            else
                            {
                                return {children: text, attrs: {rowSpan: 0}};
                            }
                        }
                    })(col);
                }
            }
            this.layout = this.dashboard.layout;

            this.filters = this.dashboard.filters;
            for(f of this.filters)
            {
                var field = f.field;
                if(f.field.indexOf('.') !== -1)
                {
                    var fs = f.field.split('.');
                    if(fs[1] == 'id') field = fs[0];
                }
                this.filterValues[f.field] = defaults[field] === '' ? undefined : defaults[field];
            }

            var fieldMap = {};
            var fs = [];
            for(var key in filters['option'])
            {
                fs.push({value: key, label: filters['option'][key].name});
                fieldMap[key] = filters['option'][key];

                var options = [];
                for(var index in filters['option'][key].options)
                {
                    options.push({value: index, label: filters['option'][key].options[index]});
                }
                fieldMap[key].options = options;
            }
            this.optionFields = fs;
            fs = [];
            for(var key in filters['date'])
            {
                fs.push({value: key, label: filters['date'][key].name});
                fieldMap[key] = filters['date'][key];
            }
            this.fieldMap = fieldMap;
        },
        itemTitle(item) {
            let title = typeof item.i.data.info == 'undefined' ? item.i.name : item.i.data.info.title;
            return title;
        },
        resizedEvent: function(i, newX, newY, newHPx, newWPx){
            this.draw(i);
        },
        draw(chart) {
            var id   = chart.id;
            var type = chart.type;
            var data = chart.data;
            if(type == 'table')
            {
                var index = this.layout.findIndex(item => item.i.id == id);
                this.layout[index].i.data = data;

                for(var col of data.columns)
                {
                    if(typeof data.rowspan[col.dataIndex] === 'undefined') continue;

                    (function(col) {
                        var index = col.dataIndex;
                        col.customRender = function(text, row, rowIndex) {
                            if(typeof data.rowspan[index][rowIndex] !== 'undefined') {
                                return {children: text, attrs: {rowSpan: data.rowspan[index][rowIndex]}};
                            }
                            else
                            {
                                return {children: text, attrs: {rowSpan: 0}};
                            }
                        }
                    })(col);
                }
            }
            else if(type.indexOf('Report') !== -1)
            {
                var report = '';
                for(let table of data.tables)
                {
                    report += "<table class='table'>";
                    for (let tr of table)
                    {
                        report += "<tr>";
                        for(let td of tr)
                        {
                            let colspan = typeof td.colspan !== 'undefined' ? ' colspan="' + td.colspan + '"' : '';
                            let rowspan = typeof td.rowspan !== 'undefined' ? ' rowspan="' + td.rowspan + '"' : '';
                            report += "<td class='" + (typeof td.cls !== 'undefined' ? td.cls : '') + "' " + rowspan + colspan + ">" + td.value + "</td>";
                        }
                        report += "</tr>";
                    }
                    report += "<table>";
                }
                report += '<div>' + data.info.desc + '</div>';
                $('#chart' + id).html(report);
            }
            else
            {
                /*
                var chart = echarts.init(document.getElementById('chartbox-' + id));
                chart.setOption(data, true);
                chart.resize();

                var index = this.layout.findIndex(item => item.i.id == id);
                this.layout[index].i.data = data;
                */
            }
        },
        changeFilter(field) {
            if(['project.id', 'execution.id', 'build.id'].indexOf(field) !== -1) this.switchOption(field, this.filterValues[field]);

            var filterValues = {};
            for(let i in this.filterValues)
            {
                filterValues[i] = this.filterValues[i];
            }
            this.filterValues = filterValues;
        },
        switchOption(field, val) {
            var that = this;
            var filters = [];
            for(let f of this.filters)
            {
                filters.push(f.field);
            }

            $.get(createLink('chart', 'ajaxGetOptions', 'field=' + field + '&val=' + val + '&filters=' + filters.join(',')), function(data) {
                data = JSON.parse(data);
                for(let type in data.options)
                {
                    that.$set(that.sysOptions, type, data.options[type]);

                    var defaultValue = data.options[type].length > 0 && data.defaults[type] ? data.defaults[type] : undefined;
                    if(typeof that.filterValues[type] !== 'undefined') that.filterValues[type] = defaultValue;
                    if(typeof that.filterValues[type + '.id'] !== 'undefined') that.filterValues[type + '.id'] = defaultValue;
                }
            })
        },
        reset() {
            this.filterValues = {};
        },
        search() {
            var data = {};
            for(let key in this.filterValues)
            {
                if(Array.isArray(this.filterValues[key]))
                {
                    data[key] = typeof this.filterValues[key][0] === 'object' ? [this.filterValues[key][0].format('YYYY-MM-DD'), this.filterValues[key][1].format('YYYY-MM-DD')] : this.filterValues[key];
                }
                else
                {
                    data[key] = typeof this.filterValues[key] === 'object' ? this.filterValues[key].format('YYYY-MM-DD') : this.filterValues[key];
                }
            }

            var that = this;
            $.post(createLink('dashboard', 'ajaxGetLayout', 'dashboardID=' + this.dashboard.id), {filters: data}, function(data) {
                that.layout = JSON.parse(data);
                that.drawCharts();
                that.autoLayout();
            })
        },
        design() {
            window.location.href = createLink('dashboard', 'design', 'id=' + this.dashboard.id);
        },
        exportData() {
        },
        editReport(chart) {
            let that = this;
            this.settingVisible = false;
            modal = new $.zui.ModalTrigger({title: lang.editReport, custom: function(el) {
                let info = chart.data.info;
                let html = '<form id="edit" onsubmit="javascript:saveReport(\'' + chart.id + '\');return false;">';
                html += '<table class="table table-form">';
                html += '<tr><th>报告标题</th><td><input type="text" name="title" id="reportTitle" value="' + info.title + '" class="form-control" autocomplete="off"></td></tr>';
                html += '<tr><th>自定义数据</th><td><textarea id="reportDesc" class="kindeditor" rows="10">' + info.desc + '</textarea></td></tr>';
                html += '<tr><th></th><td><button class="btn btn-wide btn-primary form-saveCondition" type="submit">保存</button></td></tr>';
                html += '</table>';
                html += '</form>';
                return html;
            }});

            modal.show({onShow: function() {
              setTimeout(function() {
                  let options = {
                      cssPath: [config.themeRoot + 'zui/css/min.css'],
                      width: '100%',
                      height: '200px',
                      items: ['formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'code', 'link', 'table', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'],
                      filterMode: true,
                      bodyClass: 'article-content',
                      urlType: 'absolute',
                      allowFileManager: true,
                      cssData: 'html,body {background: none}.article-content{overflow:visible}.article-content,▫ .article-content table td, .article-content table th {line-height: 1.3846153846; font-size: 13px;} .article-content .table-auto {width: auto!important; max-width: 100%;}',
                      placeholderStyle: {fontSize: '13px', color: '#888'},
                      syncAfterBlur: true,
                      spellcheck: false
                  };
                  KindEditor.create('textarea.kindeditor', options);
              }, 20)
              return false;
            }})
            modal.show();
        },
        setInfo(chartID, title, desc) {
            for(var index in this.layout)
            {
                if(chartID == this.layout[index].i.id) {
                    var chart = this.layout[index].i.data;
                    chart.info = {title: title, desc: desc}
                    this.layout[index].i.data = chart;
                    break;
                }
            }
            modal.close();

            this.drawCharts();
            this.autoLayout();
        },
        loadFilterMap() {
            var fieldsMap = {};

            var fs = [];
            for(var key in filters['option'])
            {
                fs.push({value: key, label: filters['option'][key].name});
                fieldsMap[key] = {name: filters['option'][key].name, type: filters['option'][key].type, options: filters['option'][key].options};
                var options = [];
                for(var index in filters['option'][key].options)
                {
                    options.push({value: index, label: filters['option'][key].options[index]});
                }
                fieldsMap[key].options = options;
            }
            this.optionFields = fs;

            fs = [];
            for(var key in filters['date'])
            {
                fs.push({value: key, label: filters['date'][key].name});
                fieldsMap[key] = filters['date'][key];
            }

            //this.dateFields = fs;
            this.fieldsMap = fieldsMap;
            for(let i in this.filters) {
                if(typeof this.filters[i].name === 'undefined') this.filters[i].name = this.fieldsMap[this.filters[i].field].name;
            }
        },
        /**
         * Resize all items on the grid to fit their child's content
         * and triggers a layout update on the grid
         *
         * @constructor
         */
        autoLayout(){
            var that = this;
            setTimeout(function() {
                for(let i in that.layout)
                {
                    let x = that.layout[i];
                    if(x.i.type.indexOf('Report') === -1 && x.i.type != 'table') continue;

                    let widget = that.$refs[`widget_`+x.i.id][0];
                    let widgetInnerElement = widget.$slots.default;
                    let height = 0;
                    for(let e of widgetInnerElement)
                    {
                        if(e.elm.clientHeight) height += e.elm.clientHeight;
                    }
                    that.layout[i]['h'] = height/37.5;
                }

                // -- Refresh grid
                that.$refs.gridlayout.layoutUpdate();
            }, 500);
        }
    }
})
