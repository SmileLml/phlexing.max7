let isFirstMessage = true;
let user = '';
let socket = null;

$(function()
{
    $.getLib([config.webRoot + 'js/xterm/xterm.js', config.webRoot + 'js/xterm/addon-fit.js'], {root: false}, function()
    {
        const rows = Math.floor((window.innerHeight - 5) / 17);
        terminal   = new Terminal({cursorBlink: false, rows: rows});
        terminal.open(document.getElementById('terminal'));

        const fitAddon = new FitAddon.FitAddon();
        terminal.loadAddon(fitAddon);
        fitAddon.fit()
        terminal.focus();

        initWebsocket();

        terminal.onResize(e => {
            sendWebsocketMessage({"type": "resize", "rows": e.rows, "cols": e.cols});
        });

        terminal.onData(e => {
            sendWebsocketMessage({"type": "input", "input": e});
        });

        terminal.paste = function (text) {
            sendWebsocketMessage({"type": "input", "input": text});
        };

    });
})

function sendWebsocketMessage(data) {
    if (socket && socket.readyState === 1)
    {
        socket.send(JSON.stringify(data));
    }
    if (socket && socket.status === 403)
    {
        console.error('您没有权限');
        return false;
    }
}

function initWebsocket() {
    const rows = Math.floor((window.innerHeight - 5) / 17);
    const cols = Math.floor((window.innerWidth - 5) / 9);
    const url  = webSocketURL + `&rows=${rows}&columns=${cols}`;
    socket = new WebSocket(url);
    socket.onclose = e => {
        let connectionError = instanceLang.errors.connectClosed;
        if(e.reason && instanceLang.errors.connectErrors[e.reason]) connectionError = instanceLang.errors.connectErrors[e.reason];

        zui.Modal.confirm({message: connectionError, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(res)
                {
                    window.location.reload();
                }
                else
                {
                    window.close();
                }
            });
    };

    socket.onerror = () => {
        zui.Modal.alert({message: instanceLang.errors.connectFailed, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(function(){window.close();});
    };

    socket.addEventListener('message', function (event) {
        const response = atob(event.data);
        terminal.write(decodeURIComponent(escape(response)));
    });
}
