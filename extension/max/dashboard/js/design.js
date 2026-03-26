Vue.component("v-chart", VueECharts);
var mouseXY = {"x": null, "y": null};
var dragPos = {"x": null, "y": null, "w": 6, "h": 10, "i": {id: "", data: {}}};

var app = new Vue({
    el: '#main',
    data: {
        dashboard: {},
        layout: [],
        draggable: true,
        resizable: true,
        maxHeight: 700,
        showModal: false,
        filters: [],
        optionFields: [],
        dateFields: [],
        fieldMap: {},
        fieldsMap: {},
        filter: {type: 'select', multiple: '0', field: ''},
        options: [],
        filterValues: {},
        multiples: [],
        index: 0
    },
    mounted() {
        document.addEventListener("dragover", function (e) {
            mouseXY.x = e.clientX;
            mouseXY.y = e.clientY;
        }, false);
        var that = this;
        setTimeout(function() {
            for(var item of that.layout)
            {
                var chart = item.i;
                that.draw(chart.id, chart.type, chart.data);
            }
        }, 500)
    },
    created() {
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

        var ms = [];
        for(var key in lang.multipleList)
        {
            ms.push({value: key, label: lang.multipleList[key]});
        }
        this.multiples = ms;

        this.loadFilterMap();
    },
    watch: {
    },
    methods: {
        loadFilterMap() {
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
            this.dateFields = fs;
            this.fieldMap = fieldMap;
        },
        itemTitle(item) {
            let result = item.i.name;
            return result;
        },
        resizedEvent: function(i, newX, newY, newHPx, newWPx){
            this.draw(i.id, i.type, i.data);
        },
        drag: function (e) {
            let parentRect = document.getElementById('dashboard-container').getBoundingClientRect();
            let mouseInGrid = false;
            if (((mouseXY.x > parentRect.left) && (mouseXY.x < parentRect.right)) && ((mouseXY.y > parentRect.top) && (mouseXY.y < parentRect.bottom))) {
                mouseInGrid = true;
            }
            if (mouseInGrid === true && (this.layout.findIndex(item => item.i.id == dragPos.i.id)) === -1) {
                var chartID = e.srcElement.id.split('-')[1];
                var chart = charts.find(ele => ele.id == chartID);
                chart.data = {id: "", columns: [], source: []};
                dragPos.i = chart;

                this.layout.push({
                    x: (this.layout.length * 2) % (this.colNum || 12),
                    y: this.layout.length + (this.colNum || 12), // puts it at the bottom
                    w: 6,
                    h: 10,
                    i: chart,
                });
            }
            let index = this.layout.findIndex(item => item.i.id == dragPos.i.id);
            if (index !== -1) {
                try {
                    this.$refs.gridlayout.$children[this.layout.length].$refs.item.style.display = "none";
                } catch {
                }
                let el = this.$refs.gridlayout.$children[index];
                el.dragging = {"top": mouseXY.y - parentRect.top, "left": mouseXY.x - parentRect.left};
                let new_pos = el.calcXY(mouseXY.y - parentRect.top, mouseXY.x - parentRect.left);
                if (mouseInGrid === true) {
                    this.$refs.gridlayout.dragEvent('dragstart', dragPos.i, new_pos.x, new_pos.y, 6, 10);
                    //dragPos.i = String(index);
                    dragPos.x = this.layout[index].x;
                    dragPos.y = this.layout[index].y;
                }
                if (mouseInGrid === false) {
                    this.$refs.gridlayout.dragEvent('dragend', dragPos.i, new_pos.x, new_pos.y, 6, 10);
                    this.layout = this.layout.filter(obj => obj.i.id != chartID);
                }
            }
        },
        dragend: function (e) {
            let parentRect = document.getElementById('dashboard-container').getBoundingClientRect();
            let mouseInGrid = false;
            if (((mouseXY.x > parentRect.left) && (mouseXY.x < parentRect.right)) && ((mouseXY.y > parentRect.top) && (mouseXY.y < parentRect.bottom))) {
                mouseInGrid = true;
            }
            if (mouseInGrid === true) {
                // alert(`Dropped element props:\n${JSON.stringify(dragPos, ['x', 'y', 'w', 'h'], 2)}`);
                this.$refs.gridlayout.dragEvent('dragend', dragPos.i, dragPos.x, dragPos.y, 6, 10);
                this.layout = this.layout.filter(obj => obj.i.id != dragPos.i.id);
                this.layout.push({
                    x: dragPos.x,
                    y: dragPos.y,
                    w: 6,
                    h: 10,
                    i: dragPos.i,
                });
                this.$refs.gridlayout.dragEvent('dragend', dragPos.i, dragPos.x, dragPos.y, 6, 10);
                try {
                    this.$refs.gridlayout.$children[this.layout.length].$refs.item.style.display="block";
                } catch {
                }
            }
            this.genChart(dragPos.i);
            dragPos.i = {id: ''};
            this.refreshFilter();
        },
        genChart(chartdata) {
            var that = this;

            var filters = chartdata.filters ? JSON.parse(chartdata.filters) : [];
            var filterValues = {};
            for(let f of filters) filterValues[f.field] = 0;

            $.post(createLink('chart', 'ajaxGenChart'), {
                dataset: chartdata.dataset,
                type: chartdata.type,
                settings: JSON.parse(chartdata.settings),
                filterValues: filterValues,
            }, function(data) {
                data = JSON.parse(data);
                chartdata.data = data;
                that.draw(chartdata.id, chartdata.type, data);
            })
        },
        refreshFilter() {
            var datasets = [];
            for(var item of this.layout) datasets.push(item.i.dataset);
            var that = this;
            $.post(createLink('dashboard', 'ajaxGetFilters', "datasets=" + datasets.join(',')), function(data) {
                filters = JSON.parse(data);
                that.loadFilterMap();
            })
        },
        draw(id, type, data) {
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
        setFilter(type, field) {
            this.filter = typeof field !== 'undefined' ? this.filters[index] : {field: '', type: type, multiple: '0'};
            this.showModal = true;
        },
        changeFilter() {
        },
        resetFilter() {
            this.showModal = false;
        },
        saveFilter(field) {
            if(!this.filter.field) return;

            if(['dept.id', 'casemodule.id', 'storymodule.id', 'taskmodule.id'].indexOf(this.filter.field) !== -1)
            {
                this.filter.type = 'tree';
            }

            var index = this.filters.findIndex(item => item.field == field);
            if(index === -1)
            {
                this.filters.push(this.filter);
            }
            else
            {
                this.$set(this.filters, index, this.filter);
            }
            this.showModal = false;
        },
        removeFilter: function (val) {
            const index = this.filters.map(item => item.field).indexOf(val);
            delete this.filterValues[val];
            this.filters.splice(index, 1);
        },
        removeChart: function (val) {
            const index = this.layout.map(item => item.i.id).indexOf(val);
            this.layout.splice(index, 1);
            var that = this;
            setTimeout(function() {
                for(var item of that.layout)
                {
                    var chart = item.i;
                    that.draw(chart.id, chart.type, chart.data);
                }
            }, 10)
        },
        save() {
            var that = this;
            for(let index in this.layout) {
                this.layout[index].i = {id: this.layout[index].i.id};
            }
            $.post(createLink('dashboard', 'design', 'chartID=' + this.dashboard.id), {
                layout: JSON.stringify(that.layout),
                filters: JSON.stringify(that.filters),
            },function(data) {
                window.location.href = createLink('dashboard', 'view', "id=" + that.dashboard.id);
            })
        },
        loadFilterMap() {
            var fieldsMap = {};

            var fs = [];
            for(var key in filters['option'])
            {
                fs.push({value: key, label: filters['option'][key].name});
                fieldsMap[key] = filters['option'][key];
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
            this.dateFields = fs;
            this.fieldsMap = fieldsMap;
            for(let i in this.filters) {
                if(typeof this.filters[i].name === 'undefined') this.filters[i].name = this.fieldsMap[this.filters[i].field].name;
            }
        },
    }
})
