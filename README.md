# better-uptime-bulk-update

Use the Better Uptime API to update monitor settings in bulk

API Details: https://docs.betteruptime.com/api/monitors-api

This was built to disable the 14-day domain renewal reminder on 160+ monitors 

Note: This may not run on GridPane with the 7G WAF setting "Block Bad Query Strings" enabled. For example "null" is blocked in the example below.

**Inputs**

- API Key:    From https://betteruptime.com/
- Page:       Defaults to 1. Increment as needed to cycle through all pages
- Data Key:   See example API result below. Example entry: *domain_expiration*
- Data Value: See example API result below. Example entries: *null, 1, 2, 3, 7, 14, 30, 60*)



**Example Monitor result**

      {
        "data":{
          "id":"123456",
          "type":"monitor",
          "attributes":{
            "url":"https://razorfrog.com.com",
            "pronounceable_name":"razorfrog.com",
            "monitor_type":"status",
            "monitor_group_id":12345,
            "last_checked_at":"2022-02-04T14:46:15.000Z",
            "status":"up",
            "policy_id":null,
            "required_keyword":"",
            "verify_ssl":true,
            "check_frequency":60,
            "call":false,
            "sms":false,
            "email":true,
            "push":true,
            "team_wait":600,
            "http_method":"get",
            "request_timeout":30,
            "recovery_period":0,
            "request_headers":[

            ],
            "request_body":"",
            "follow_redirects":true,
            "remember_cookies":false,
            "paused_at":null,
            "created_at":"2020-10-15T22:53:52.052Z",
            "updated_at":"2022-02-03T15:19:32.719Z",
            "ssl_expiration":7,
            "domain_expiration":null,
            "regions":[
              "us"
            ],
            "expected_status_codes":[

            ],
            "port":null,
            "confirmation_period":300
          },
          "relationships":{
            "policy":{
              "data":null
            }
          }
        }
      }
