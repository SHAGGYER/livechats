export default Helpers;

let Helpers = {
    scrollToBottom: function (selector) {
        const container = $(selector);

        if (container[0])
            container[0].scrollTop = container[0].scrollHeight;
        /*
        container.stop().animate({
            scrollTop: container[0].scrollHeight
        }, 800);
        */
    },

    validateEmail: function(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email.toLowerCase());
    },

    createRandomString: function(length)
    {
        return Math.random().toString(36).substr(2, length);
    },

    range: function(min,max){
        const array = [];
        let j = 0;
        for(let i = min; i <= max; i++){
            array[j] = i;
            j++;
        }
        return array;
    },

    attachScrollWatcher: function (element, offset, callback) {


        const contentHeight = element.scrollHeight;
        const yOffset = element.scrollTop;
        const y = yOffset + element.offsetHeight;

        if (y >= ( contentHeight - offset ))
        {
            console.log('scrolled to bottom');
            callback();
        }
    },

    attachTopScrollWatcher: function (element, offset, callback) {
        const contentHeight = element.scrollHeight;
        const yOffset = element.scrollTop;
        const y = yOffset + element.offsetHeight;

        if (yOffset <= 0)
        {
            callback(contentHeight);
        }
    },

    restorePositionFromTop: function (oldScrollHeight, element) {
        //console.log(oldScrollHeight + ' ' + element.scrollHeight);
        $(element).scrollTop(element.scrollHeight - oldScrollHeight);
    }
};