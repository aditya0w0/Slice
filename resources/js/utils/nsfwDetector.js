import * as nsfwjs from 'nsfwjs';

/**
 * NSFWJS Content Moderation Utility
 * Detects inappropriate content in images before upload
 */

let model = null;
let modelLoading = false;

/**
 * Thresholds for content detection
 * Rule: Block explicit content (genitals, breasts, anal) but allow artistic nudity
 * - Porn/Hentai = explicit sexual content (genitals visible) → BAN
 * - Sexy = artistic nudity, suggestive content → ALLOW (unless extreme)
 */
const THRESHOLDS = {
    porn: 0.6,      // 60% confidence = ban (strict - blocks dicks, pussy, anal)
    hentai: 0.6,    // 60% confidence = ban (strict - blocks animated porn)
    sexy: 0.95,     // 95% confidence = ban (VERY lenient - allows artistic nudity)
};

/**
 * Load the NSFWJS model (lazy loading on first use)
 */
async function loadModel() {
    if (model) return model;
    if (modelLoading) {
        // Wait for existing load to complete
        while (modelLoading) {
            await new Promise(resolve => setTimeout(resolve, 100));
        }
        return model;
    }

    try {
        modelLoading = true;
        console.log('🔍 Loading NSFWJS model...');
        model = await nsfwjs.load();
        console.log('✅ NSFWJS model loaded successfully');
        return model;
    } catch (error) {
        console.error('❌ Failed to load NSFWJS model:', error);
        throw new Error('Content moderation system unavailable');
    } finally {
        modelLoading = false;
    }
}

/**
 * Check if an image contains inappropriate content
 * @param {File|HTMLImageElement} imageInput - Image file or element to check
 * @returns {Promise<{safe: boolean, predictions: Array, reason?: string}>}
 */
export async function checkImage(imageInput) {
    try {
        const nsfwModel = await loadModel();
        
        // Convert File to HTMLImageElement if needed
        let imgElement;
        if (imageInput instanceof File) {
            imgElement = await fileToImage(imageInput);
        } else {
            imgElement = imageInput;
        }

        // Run prediction
        const predictions = await nsfwModel.classify(imgElement);
        console.log('🔍 NSFWJS Predictions:', predictions);

        // Check for explicit content
        const pornScore = predictions.find(p => p.className === 'Porn')?.probability || 0;
        const hentaiScore = predictions.find(p => p.className === 'Hentai')?.probability || 0;
        const sexyScore = predictions.find(p => p.className === 'Sexy')?.probability || 0;

        // Simple rule: If detected explicit content, BAN IT
        if (pornScore > THRESHOLDS.porn) {
            return {
                safe: false,
                predictions,
                reason: 'Explicit content detected (pornographic)',
                scores: { porn: pornScore, hentai: hentaiScore, sexy: sexyScore }
            };
        }

        if (hentaiScore > THRESHOLDS.hentai) {
            return {
                safe: false,
                predictions,
                reason: 'Explicit content detected (hentai)',
                scores: { porn: pornScore, hentai: hentaiScore, sexy: sexyScore }
            };
        }

        if (sexyScore > THRESHOLDS.sexy) {
            return {
                safe: false,
                predictions,
                reason: 'Suggestive content detected',
                scores: { porn: pornScore, hentai: hentaiScore, sexy: sexyScore }
            };
        }

        // Image is safe
        return {
            safe: true,
            predictions,
            scores: { porn: pornScore, hentai: hentaiScore, sexy: sexyScore }
        };

    } catch (error) {
        console.error('Error checking image:', error);
        // On error, allow the image (fail open) but log it
        return {
            safe: true,
            predictions: [],
            error: error.message
        };
    }
}

/**
 * Convert a File to an HTMLImageElement
 */
function fileToImage(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = e.target.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

/**
 * Get the placeholder image for blocked content
 */
export function getBlockedContentPlaceholder() {
    // Return path to SpongeBob placeholder
    return '/images/blocked-content.png';
}

/**
 * Preload the model in the background (optional optimization)
 */
export function preloadModel() {
    loadModel().catch(err => {
        console.warn('Failed to preload NSFWJS model:', err);
    });
}
