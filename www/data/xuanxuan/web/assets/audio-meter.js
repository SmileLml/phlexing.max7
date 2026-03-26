// origin code from https://stackoverflow.com/questions/62702721/how-to-get-microphone-volume-using-audioworklet

registerProcessor(
    'meter',
    class extends AudioWorkletProcessor {
        _smoothingFactor = 0.8;
        _volume = 0;
        _updateIntervalInMS = 50;
        _nextUpdateFrame;

        constructor() {
            super();
            this._nextUpdateFrame = this._updateIntervalInMS;
            if (this.port) {
                this.port.onmessage = (event) => {
                    if (event.data.updateIntervalInMS) {
                        this._updateIntervalInMS = event.data.updateIntervalInMS;
                    }
                };
            }
        }

        get intervalInFrames() {
            return (this._updateIntervalInMS / 1000) * sampleRate;
        }

        process(inputs) {
            const input = inputs[0];

            // Note that the input will be down-mixed to mono; however, if no inputs are
            // connected then zero channels will be passed in.
            if (0 < input.length) {
                const samples = input[0];
                // Calculated the squared-sum.
                const sum = samples.reduce((accumulator, currentValue) => {
                    return accumulator + (currentValue * currentValue);
                }, 0);

                // Calculate the RMS(Root Mean Square) level and update the volume.
                const rms = Math.sqrt(sum / samples.length);
                this._volume = Math.max(rms, this._volume * this._smoothingFactor);

                // Update and sync the volume property with the main thread.
                this._nextUpdateFrame -= samples.length;
                if (0 > this._nextUpdateFrame) {
                    this._nextUpdateFrame += this.intervalInFrames;
                    this.port.postMessage({volume: this._volume});
                }
            }
            return true;
        }
    },
);
