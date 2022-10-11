function ZSelect(element) {
    this.select = element;
    this.data = {
        options: [],
        selectedOption: null,
        lastIndex:0,
        classList: '',
        indexes: {},
    };
    this.validate = function(){
        var self = this;
        self.data.options = self.select.find('option');
        var validated = false;
        if (self.data.options.length>1){
            $.each(self.data.options, function(index, option){
                if ($(option).prop('value')) validated = true;
            });
        }
        return validated;
    };
    this.init = function(){
        var self = this;
        if (self.validate()){
            self.btn = $('<button type="button" class="z-select-btn"><span class="z-select-title"></span></button>');
            self.title = self.btn.find('.z-select-title');
            self.addSelectOptions();
            self.addEvents();
            self.selectChangeFunction();
            self.btn.insertAfter(self.select);
        }
        else return false;
    };
    this.addSelectOptions = function(){
        var self=this,
            classListArray = [],
            selectedOption = null,
            optionKey=0;
        $.each(self.data.options, function(index, option){
            var $option = $(option),
                optionValue = $option.prop('value'),
                dataClass = $option.data('class'),
                optionClasses;
            if (!optionValue) return true;
            if (typeof dataClass === 'undefined' || !dataClass) {
                optionClasses = null
            }
            else {
                dataClass = $.trim(dataClass).replace(/\s{2,}/g, ' ');
                var thisClasses = dataClass.split(' '),
                    optionClassesArray = [];
                for (var i in thisClasses) {
                    var thisClass = thisClasses[i];
                    if (!classListArray.includes(thisClass)) classListArray.push(thisClass);
                    if (!optionClassesArray.includes(thisClass)) optionClassesArray.push(thisClass);
                }
                optionClasses = optionClassesArray.join(' ');
            }
            if ($option.prop('selected')) {
                self.data.selectedOption = i;
            }
            self.data.options[optionKey] = {
                title: $option.html(),
                classes: optionClasses,
                value: optionValue,
            };
            self.data.indexes[optionValue] = optionKey;
            optionKey++;
        });
        self.data.classList = classListArray.join(' ');
        self.data.lastIndex = self.data.options.length - 1;
        if (self.data.selectedOption===null) self.data.selectedOption=0;
    };
    this.addEvents = function(){
        var self = this;
        self.select.on('change', function(){
            self.selectChangeFunction()
        });
        self.btn.on('click', function(){
            self.btnClickFunction();
        });
    };
    this.selectChangeFunction = function(){
        var self = this,
            option = self.getByValue(self.select.val()),
            selectedOption = option.option;
        self.btn.removeClass(self.data.classList).addClass(selectedOption.classes);
        self.title.html(selectedOption.title);
        self.data.selectedOption = option.index;
    };
    this.btnClickFunction = function(){
        var self = this,
            selectedOption = self.data.selectedOption,
            newIndex;
        if (!self.select.is(':disabled')) {
            if (selectedOption < self.data.lastIndex) {
                newIndex = selectedOption+1;
            }
            else newIndex = 0;
            self.select.val(self.data.options[newIndex].value);
            self.selectChangeFunction();
        }

    };
    this.getByValue = function(value) {
        var index = this.data.indexes[value],
            option = this.data.options[index];
        return (typeof option !== 'undefined')?{index:index,option:option}:false;
    };
    this.init();
}
$.each($('select.z-select'), function(){
    new ZSelect($(this));
});