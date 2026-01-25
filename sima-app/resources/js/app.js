import './bootstrap';
import { Html5Qrcode } from 'html5-qrcode';

let qrScanner = null;
let lastDecodedText = null;
let qrInitInProgress = false;
let qrStarted = false;

const destroyQrScanner = async () => {
	if (qrScanner) {
		try {
			await qrScanner.stop();
		} catch (error) {
			// ignore stop errors
		}
		try {
			qrScanner.clear();
		} catch (error) {
			// ignore clear errors
		}
		qrScanner = null;
	}
	qrStarted = false;
	qrInitInProgress = false;
};

const initQrScanner = async () => {
	const target = document.getElementById('qr-reader');
	if (!target) {
		return;
	}

	if (qrInitInProgress || qrStarted) {
		return;
	}

	if (target.dataset.qrInitialized === 'true') {
		return;
	}

	if (target.querySelector('video, canvas')) {
		return;
	}

	qrInitInProgress = true;

	await destroyQrScanner();

	target.innerHTML = '';
	qrScanner = new Html5Qrcode('qr-reader', { verbose: false });

	try {
		await qrScanner.start(
			{ facingMode: 'environment' },
			{
				fps: 15,
				aspectRatio: 1.0,
				disableFlip: false,
				experimentalFeatures: { useBarCodeDetectorIfSupported: true },
				qrbox: (viewfinderWidth, viewfinderHeight) => {
					const size = Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.6);
					return { width: size, height: size };
				},
			},
			(decodedText) => {
				if (!decodedText || decodedText === lastDecodedText) {
					return;
				}

				lastDecodedText = decodedText;

				if (window.Livewire?.dispatch) {
					window.Livewire.dispatch('assetScanned', { code: decodedText });
				} else if (window.Livewire?.emit) {
					window.Livewire.emit('assetScanned', decodedText);
				}

				setTimeout(() => {
					lastDecodedText = null;
				}, 1500);
			},
			() => {
				// ignore decode errors
			}
		);
		qrStarted = true;
		target.dataset.qrInitialized = 'true';
	} catch (error) {
		// Camera might be blocked; user can still input manually
		// eslint-disable-next-line no-console
		console.warn('QR scanner init failed:', error);
		target.dataset.qrInitialized = 'false';
	} finally {
		qrInitInProgress = false;
	}
};

document.addEventListener('livewire:load', () => {
	initQrScanner();
});

document.addEventListener('livewire:navigated', async () => {
	await destroyQrScanner();
	initQrScanner();
});

document.addEventListener('livewire:navigating', async () => {
	const target = document.getElementById('qr-reader');
	if (target) {
		target.dataset.qrInitialized = 'false';
	}
	await destroyQrScanner();
});

window.addEventListener('beforeunload', async () => {
	await destroyQrScanner();
});
