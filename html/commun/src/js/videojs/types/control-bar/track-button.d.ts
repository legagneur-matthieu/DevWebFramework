export default TrackButton;
/** @import Player from '../player' */
/**
 * The base class for buttons that toggle specific  track types (e.g. subtitles).
 *
 * @extends MenuButton
 */
declare class TrackButton extends MenuButton {
    updateHandler_: Function;
    cleanupHandler_: Function;
    /**
     * Remove all track list event listeners without triggering a full dispose.
     * Used as the player 'dispose' handler and called by dispose() before super.
     *
     * @private
     */
    private cleanupTrackListeners_;
}
import MenuButton from '../menu/menu-button.js';
//# sourceMappingURL=track-button.d.ts.map