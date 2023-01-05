

  (function ($, Drupal,drupalSettings) {
    Drupal.behaviors.Chejara = {
      attach: function (context, settings) {
        var protocol = $(location).attr("protocol");
        var hostname = $(location).attr("hostname");
        var origin = $(location).attr("origin");
        var pathname = $(location).attr("pathname");
        var pathname_split = pathname.split("/");

        if (hostname == 'localhost'){
          var url = origin + "/" + pathname_split[1];
        } else {
          var url = origin
        }
        console.log(url);
        var grade, subject, level = null;
        if (drupalSettings.role_fields){
          var nid = drupalSettings.role_fields.id;
          var grade = drupalSettings.role_fields.grade;
          var level = drupalSettings.role_fields.level;
          var subject = drupalSettings.role_fields.subject;
          var actor = drupalSettings.role_fields.actor;
        }

        if (context !== window.document) return;

        if ( window.H5P && window.H5P.externalDispatcher )
        {
          H5P.externalDispatcher.on('xAPI', function (event) {
            //console.log(event.data.statement);
            postStatement(event.data.statement);
            console.log('Statement is sent');
          });
        }
        // CREATE COMMENT START //
    function postStatement(data) {
        // Get token for current user
          var jsonData = {
            "level":level,
            "grade":grade,
            "subject":subject,
            "actor":actor,
            "verb":data.verb.display['en-US'],
            "nid":nid,
            "data":data,
            "answer_check":0,
            "duration":0,
          };

          if (data.result){

           // Duration is stored in PT24.34S format.
           jsonData["duration"] = data.result.duration.split('T').pop().split('S')[0];

            console.log(jsonData["duration"]);
           if (data.result.success)
            jsonData["answer_check"] = 1;
          }

          $.get(url+"/session/token").done(function(response) {
            var csrfToken = response;
            console.info(csrfToken);
          // Post
          $.ajax({
            url: url+'/api/role-fields-roles-fields?_format=json',
            method: 'POST',
            crossDomain: true,
            data: JSON.stringify(jsonData),
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': csrfToken,
            },
            success: function (createdComment) {
              console.info(createdComment);
              console.info(jsonData);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.info(textStatus);
            }
          });
        });

    }



      }
    };



  })(jQuery, Drupal,drupalSettings);
