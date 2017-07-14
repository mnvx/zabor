var Survey = function () {
    var self = {
        number: 0,
        questionsContainerName: '.row.survey.questions',
        questionTemplateName: '#survey-question-template'
    };
    return {
        addQuestion: function (event, id) {
            event.preventDefault();
            var questionTemplate = $(self.questionTemplateName).html();
            self.number++;
            var question = Mustache.render(questionTemplate, {
                number: self.number
            });
            $(self.questionsContainerName).append(question);
            $('#survey_question_type_' + self.number).material_select();
        },

        deleteQuestion: function (event, id) {
            event.preventDefault();
            $(event.target).parents('.question').remove();
        },

        saveButton: function (event) {
            event.preventDefault();
            $(self.commentName + ' form').request('onSaveCommentButton', {
                data: {'parent_id': self.parent_id},
                success: function (data) {
                    if (data['message']) {
                        Comment.addMessage(data['message'])
                    } else if (data['content']) {
                        Comment.addComment(data['content']);
                        Comment.cancel();
                        $(self.commentName + ' form').trigger('reset');
                    } else{
                        $(self.commentName + ' form').trigger('reset');
                    }
                }
            });
        },

        addComment: function (content) {
            var commentBlock = $('#comment-' + self.parent_id);
            if (self.parent_id == null) {
                $('.comments ul:eq(0)').append(content);
            } else if (commentBlock.next('ul').length) {
                commentBlock.next('ul').append(content);
            } else {
                commentBlock.append($('<ul>').html(content));
            }
            this.countIncrement()
        },

        countIncrement: function () {
            var data = $('#comments-count').text();
            if ($.isNumeric(data)) {
                $('#comments-count').text(parseInt(data) + 1);
            }

        },

        addMessage: function (data) {
            var html = $('<ul>');
            $.each(data, function (i, item) {
                html.append($('<li>').text(item[0]));
            });
            $(self.messageName).html($("<div>").addClass('alert alert-danger').append(html));
        },

        clearMessage: function () {
            $(self.messageName).empty();
        },

        cancel: function () {
            self.parent_id = null;
            this.clearMessage();
            $(self.cancelName).hide();
            $(self.commentWrapName).html($(self.commentName));

        }
    }
}();

