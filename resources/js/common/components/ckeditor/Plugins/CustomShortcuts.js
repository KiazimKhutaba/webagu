import {Plugin} from "ckeditor5";

/**
 * @see https://ckeditor.com/docs/ckeditor5/latest/framework/tutorials/crash-course/keystrokes.html#adding-keyboard-shortcuts
 */
class CustomShortcuts extends Plugin {
    init() {
        const editor = this.editor;
        const t = this.editor.t;

        // Define the custom keyboard shortcut
        editor.keystrokes.set('Ctrl+Shift+K', (data, cancel) => {
            // Your custom action here
            editor.execute('indent'); // Example: Execute the 'bold' command
            cancel(); // Prevent further processing of the keystroke
        });

        editor.keystrokes.set('Ctrl+Shift+L', (data, cancel) => {
            editor.execute('outdent');
            cancel();
        });

        editor.accessibility.addKeystrokeInfos( {
            keystrokes: [
                {
                    label: t( 'Indent' ),
                    keystroke: 'Ctrl+Shift+K'
                },
                {
                    label: t( 'Outdent' ),
                    keystroke: 'Ctrl+Shift+L'
                }
            ]
        } );
    }
}

export default CustomShortcuts;
