var ElectricMeter = function () {
    var self = {
        number: 0,
        containerName: '.row.electric-meters.counters',
        templateName: '#electric-meter-template'
    };
    return {
        addCounter: function (event, counter) {
            if (event) {
                event.preventDefault();
            }

            var template = $(self.templateName).html();
            self.number++;
            var row = Mustache.render(template, {
                number: self.number
            });
            $(self.containerName).append(row);

            var tariffField = $('#electric_meter_tariff_' + self.number);
            if (counter) {
                tariffField.val(counter.tariff_number);
                $('#electric_meter_' + self.number).val(counter.counter_number);
            }

            tariffField.material_select();
        },

        deleteCounter: function (event, id) {
            event.preventDefault();
            $(event.target).parents('.electric-meter').remove();
        },

        init: function (electricCounters) {
            for (var index in electricCounters) {
                if (electricCounters.hasOwnProperty(index)) {
                    this.addCounter(null, electricCounters[index]);
                }
            }
        }
    }
}();

$(window).load(function() {
    ElectricMeter.init(electricCounters);
});
