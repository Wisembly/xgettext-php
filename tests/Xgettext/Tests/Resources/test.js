function __(s) {
        return typeof l10n[s] != 'undefined' ? l10n[s] : s;
}
function test(param) {
        var a = __("Hello world, testing xgettext");
        func(__('Test string'));
        var reg1 = /"[a-z]+"/i;
        var reg2 = /[a-z]+\+\/"aa"/i;
        var s1 = __('string 1: single quotes');
        var s2 = __("string 2: double quotes");
        var s3 = __("/* comment in string */");
        var s4 = __("regexp in string: /[a-z]+/i");
        var s5 = xgettext( "another function" );
        var s6 = avoidme("should not see me!");
        var s7 = __("string 2: \"escaped double quotes\"");
        var s8 = __('string 2: \'escaped single quotes\'');

        var s4 = _n("singular text", "{{ count }} plural", 42);
        var s4 = _n('singular text again', "another {{ count }} plural", 7);
        var s4 = _n('singular text again', "another {{ count }} plural", 42);

        true ? __("String with (parenthesis)") : __('and another string with %weird {characters}! |&[] in the same line');
        _n("Time remaining: %1 day", "Time remaining: %1 days", obj.remaining)

        _n("Wrong plural, won't be parsed!");

        // "string in comment"
        //;

        /**
         * multiple
         * lines
         * comment
         * __("Hello world from comment")
         */
}
