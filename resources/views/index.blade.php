<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noarchive" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Index</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">

</head>
<body oncontextmenu="return false;">
<div class="wrapper">
    <table>
        <tr>
            <td>
                <h1>
                WEB SERVICE DOCUMENT
                </h1>
            </td>
        </tr>
        <tr>
            <td>
                <h2>
                    WEB SERVICE ERRORS
                </h2>
            </td>
        </tr>
        <tr>
            <td>
                message_no = 0 <br />
                message_text = 'GENERAL_ERROR' <br />
                message_description = 'Unknown error occurred' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 1 <br />
                message_text = 'EMAIL_NOT_AVAILABLE' <br />
                message_description = 'Email is not available, already taken in our database' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 2 <br />
                message_text = 'USERNAME_NOT_AVAILABLE' <br />
                message_description = 'Username is not available, already taken in our database' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 3 <br />
                message_text = 'Method not allowed' <br />
                message_description = 'Method is not allowed, not allowed service operation' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 4 <br />
                message_text = 'Invalid request' <br />
                message_description = 'Invalid request, not a valid JSON message, missing operation field in JSON message' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 5 <br />
                message_text = 'Invalid operation' <br />
                message_description = 'Invalid operation, operation field value not recognized in web service' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 6 <br />
                message_text = 'MISSING_PARAMETERS' <br />
                message_description = 'One or more parameters are missing' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 7 <br />
                message_text = 'MAXIMUM_TEMPORARY_TICKET_NUMBER' <br />
                message_description = 'Can not generate temp ticket. Ticket number has reached allowed maximum.' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 8 <br />
                message_text = 'DRAW_MODEL_IS_INACTIVE' <br />
                message_description = 'Draw model is inactive' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 9 <br />
                message_text = 'NEXT_DRAW_IS_NOT_DEFINED' <br />
                message_description = 'Next draw is not defined' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 10 <br />
                message_text = 'UNHANDLED_EXCEPTION' <br />
                message_description = 'Unhandled exception. Unknown error occurred in database' <br /><br />
            </td>
        </tr>
        <tr>
            <td>
                message_no = 11 <br />
                message_text = 'PAYOUT_TIME_EXPIRED' <br />
                message_description = 'Payout time expired' <br /><br />
            </td>
        </tr>

        <tr>
            <td>
                <h2>
                GENERAL ERRORS
                </h2>
            </td>
        </tr>
        <tr>
            <td>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    error_message = 'Method not allowed' <br />
                    error_code = 3 <br />
                    error_description = 'Method is not allowed, not allowed service operation' <br />
            </td>
        </tr>
        <tr>
            <td>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    error_message = 'Invalid request' <br />
                    error_code = 4 <br />
                    error_description = 'Invalid request, not a valid JSON message, missing operation field in JSON message' <br />
            </td>
        </tr>
        <tr>
            <td>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    error_message = 'Invalid operation' <br />
                    error_code = 5 <br />
                    error_description = 'Invalid operation, operation field value not recognized in web service' <br />
            </td>
        </tr>





        <tr>
            <td>
                <h2>
                AUTH SERVICE
                </h2>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="color: red;">
                    login (changed 25-Apr-2018 10:45, changed 10-July-2018 10:40, changed 10-July-2018 13:00, updated 06-Sep-2018 15:20) - for terminal screen
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'login', username*, password*, ip_address, device = ($_SERVER['HTTP_USER_AGENT']), subject_type_id=5*
                </h4>
                <br />
                    Database procedures:
                    <br />
                    tombola.log_in_subject(:p_username_in, :p_password_in, :p_subject_type_id, :p_ip_address, :p_login_platform)
                    <br />
                    tomboladb.core.get_subjects_details_from_session(:p_session_id_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'login' <br />
                        session_id = <br />
                        status_out = <br />
                        user_details = [ <br />
                            &ensp;&ensp;user_id = <br />
                            &ensp;&ensp;username = <br />
                            &ensp;&ensp;first_name = <br />
                            &ensp;&ensp;last_name = <br />
                            &ensp;&ensp;email = <br />
                            &ensp;&ensp;registration_date = <br />
                            &ensp;&ensp;subject_type = <br />
                            &ensp;&ensp;active = <br />
                            &ensp;&ensp;language = <br />
                            &ensp;&ensp;parent_id = <br />
                            &ensp;&ensp;parent_username = <br />
                            &ensp;&ensp;address = <br />
                            &ensp;&ensp;commercial_address = <br />
                            &ensp;&ensp;city = <br />
                            &ensp;&ensp;country_code = <br />
                            &ensp;&ensp;country_name = <br />
                            &ensp;&ensp;mobile_phone = <br />
                            &ensp;&ensp;post_code = <br />
                            &ensp;&ensp;currency = <br />
                        ] <br />
                        <!--
                        list_currency = [ <br />

                        ] <br />
                        -->
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'login' <br />
                        status_out =
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'login' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    login-cashier (created 10-July-2018 13:00, updated 01-Aug-2018 12:20, updated 06-Sep-2018 15:20) - for cashier
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'login-cashier', username*, password*, ip_address, device = ($_SERVER['HTTP_USER_AGENT']), service_code*, subject_type_id=23*
                </h4>
                <br />
                    Database procedures:
                    <br />
                    tombola.log_in_subject(:p_username_in, :p_password_in, :p_subject_type_id, :p_ip_address, :p_login_platform, :p_service_code)
                    <br />
                    tomboladb.core.get_subjects_details_from_session(:p_session_id_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'login' <br />
                        session_id = <br />
                        status_out = <br />
                        user_details = [ <br />
                            &ensp;&ensp;user_id = <br />
                            &ensp;&ensp;username = <br />
                            &ensp;&ensp;first_name = <br />
                            &ensp;&ensp;last_name = <br />
                            &ensp;&ensp;email = <br />
                            &ensp;&ensp;registration_date = <br />
                            &ensp;&ensp;subject_type = <br />
                            &ensp;&ensp;active = <br />
                            &ensp;&ensp;language = <br />
                            &ensp;&ensp;parent_id = <br />
                            &ensp;&ensp;parent_username = <br />
                            &ensp;&ensp;address = <br />
                            &ensp;&ensp;commercial_address = <br />
                            &ensp;&ensp;city = <br />
                            &ensp;&ensp;country_code = <br />
                            &ensp;&ensp;country_name = <br />
                            &ensp;&ensp;mobile_phone = <br />
                            &ensp;&ensp;post_code = <br />
                            &ensp;&ensp;currency = <br />
                            &ensp;&ensp;shift_number = <br />
                        ] <br />
                        <!--
                        list_currency = [ <br />

                        ] <br />
                        -->
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'login-cashier' <br />
                        status_out =
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'login-cashier' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    logout
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'logout', session_id*
                </h4>
                <br />
                Database procedures:
                <br >
                tombola.log_out_subject(:p_session_id)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'logout' <br />
                        status_out =
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'logout'
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'logout' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>






        <tr>
            <td>
                <h2>
                USER SERVICE
                </h2>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="color: red;">
                    personal-information
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'personal-information', session_id*
                </h4>
                Database procedures:
                <br />
                tomboladb.core.get_subjects_details_from_session(:p_session_id_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'personal-information' <br />
                        session_id = <br />
                        user_details = [ <br />
                        &ensp;&ensp;user_id = <br />
                        &ensp;&ensp;username = <br />
                        &ensp;&ensp;first_name = <br />
                        &ensp;&ensp;last_name = <br />
                        &ensp;&ensp;email = <br />
                        &ensp;&ensp;registration_date = <br />
                        &ensp;&ensp;subject_type = <br />
                        &ensp;&ensp;active = <br />
                        &ensp;&ensp;language = <br />
                        &ensp;&ensp;parent_id = <br />
                        &ensp;&ensp;parent_username = <br />
                        &ensp;&ensp;address = <br />
                        &ensp;&ensp;commercial_address = <br />
                        &ensp;&ensp;city = <br />
                        &ensp;&ensp;country_code = <br />
                        &ensp;&ensp;country_name = <br />
                        &ensp;&ensp;mobile_phone = <br />
                        &ensp;&ensp;post_code = <br />
                        &ensp;&ensp;currency = <br />
                        ] <br />
                        list_currency = [ <br />

                        ] <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'personal-information' <br />
                        status_out =
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'personal-information' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="color: red;">
                    update-personal-information
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'update-personal-information', session_id*, user_id*, username, first_name, last_name, currency,
                    email, subject_type, account_active = 1 | 0, language = en_GB, address, commercial_address, city, country_code, mobile_phone, post_code
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.get_subjects_details_from_session(:p_session_id_in)
                <br />
                tomboladb.core.update_subject(:p_subject_id_in, :p_username_in, :p_first_name_in, :p_last_name_in, :p_currency_in, :p_email_in, :p_edited_by_in, :p_player_dtype_in, :p_subject_state_in, :p_language_in, :p_address, :p_city, :p_country, :p_mobile_phone, :p_post_code, :p_commercial_address)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'update-personal-information' <br />
                        session_id = <br />
                        status_out = <br />
                        user_details = [ <br />
                        &ensp;&ensp;user_id = <br />
                        &ensp;&ensp;username = <br />
                        &ensp;&ensp;first_name = <br />
                        &ensp;&ensp;last_name = <br />
                        &ensp;&ensp;email = <br />
                        &ensp;&ensp;registration_date = <br />
                        &ensp;&ensp;subject_type = <br />
                        &ensp;&ensp;active = <br />
                        &ensp;&ensp;language = <br />
                        &ensp;&ensp;parent_id = <br />
                        &ensp;&ensp;parent_username = <br />
                        &ensp;&ensp;address = <br />
                        &ensp;&ensp;commercial_address = <br />
                        &ensp;&ensp;city = <br />
                        &ensp;&ensp;country_code = <br />
                        &ensp;&ensp;country_name = <br />
                        &ensp;&ensp;mobile_phone = <br />
                        &ensp;&ensp;post_code = <br />
                        &ensp;&ensp;currency = <br />
                    ] <br />
                        list_currency = [ <br />

                        ] <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'update-personal-information' <br />
                        status_out =
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'update-personal-information' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    change-password
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'change-password', session_id*, user_id*, password* (plain text)
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.change_password(:p_session_id_in, :p_subject_id_in, :p_password_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'change-password' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'change-password' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'change-password' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>






        <tr>
            <td>
                <h2>
                PLAYER-REPORT SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-player-tickets (changed 20-Apr-2018 14:20, changed 31-May-2018 13:50, changed 01-Jun-2018 09:05)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-player-tickets', session_id*, user_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_player_tickets(:p_session_id_in, :p_player_id)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-player-tickets' <br />
                        session_id = <br />
                        user_id = <br />
                        list_report = [ <br />
                        &ensp;&ensp;ticket_id = <br />
                        &ensp;&ensp;barcode =  <br />
                        &ensp;&ensp;payed_out = <br />
                        &ensp;&ensp;payed_out_formatted = <br />
                        &ensp;&ensp;ticket_status = <br />
                        &ensp;&ensp;ticket_status_formatted = <br />
                        &ensp;&ensp;ticket_printed = <br />
                        &ensp;&ensp;ticket_printed_formatted = <br />
                        &ensp;&ensp;draw_order_num = <br />
                        &ensp;&ensp;min_possible_win = <br />
                        &ensp;&ensp;min_possible_win_formatted = <br />
                        &ensp;&ensp;max_possible_win = <br />
                        &ensp;&ensp;max_possible_win_formatted = <br />
                        &ensp;&ensp;global_jp_code = <br />
                        &ensp;&ensp;local_jp_code = <br />
                        &ensp;&ensp;player_username = <br />
                        &ensp;&ensp;address = <br />
                        &ensp;&ensp;address2 = <br />
                        &ensp;&ensp;city = <br />
                        &ensp;&ensp;local_win = <br />
                        &ensp;&ensp;local_win_formatted = <br />
                        &ensp;&ensp;global_win = <br />
                        &ensp;&ensp;global_win_formatted = <br />
                        &ensp;&ensp;language = <br />
                        &ensp;&ensp;no_of_printings = <br />
                        &ensp;&ensp;no_of_printings_formatted = <br />
                        &ensp;&ensp;win_paid_out = <br />
                        &ensp;&ensp;win_paid_out_formatted = <br />
                        &ensp;&ensp;date_time_formatted = <br />
                        &ensp;&ensp;sum_bet = <br />
                        &ensp;&ensp;sum_bet_formatted = <br />
                        &ensp;&ensp;sum_win = <br />
                        &ensp;&ensp;sum_win_formatted = <br />
                        ] <br />
                        list_combinations = [ <br />
                        &ensp;&ensp;combination_id =  <br />
                        &ensp;&ensp;combination_type = <br />
                        &ensp;&ensp;combination_value = <br />
                        &ensp;&ensp;bet = <br />
                        &ensp;&ensp;bet_formatted = <br />
                        &ensp;&ensp;win = <br />
                        &ensp;&ensp;win_formatted = <br />
                        &ensp;&ensp;ticket_id = <br />
                        &ensp;&ensp;draw_order_num = <br />
                        &ensp;&ensp;number_of_combinations = <br />
                        &ensp;&ensp;number_of_combinations_formatted = <br />
                        ] <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-player-tickets' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-player-tickets' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-player-money-transactions (created 30-May-2018 14:00, updated 12-Sep-2018 11:45, updated 03-Oct-2018 10:00, updated 03-Oct-2018 11:40)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-player-money-transactions', session_id*, user_id*, start_date (first day of month default), end_date = (current day default), page_number = 1 (1 by default), number_of_results = 100 (100 by default)
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.list_subjects_money_transactions(:p_session_id_in, :p_subject_id_in, :p_start_date, :p_end_date, :p_page_number, :p_number_of_results)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-player-money-transactions' <br />
                        session_id = <br />
                        user_id = <br />
                        start_date = <br />
                        end_date = <br />
                        page_number = <br />
                        number_of_results = <br />
                        report_list_transactions_rows_count = <br />
                        report_list_transactions = [ <br />
                        &ensp;&ensp;date_time = <br />
                        &ensp;&ensp;amount = <br />
                        &ensp;&ensp;amount_formatted = <br />
                        &ensp;&ensp;transaction_type = <br />
                        &ensp;&ensp;transaction_id = <br />
                        &ensp;&ensp;barcode = <br />
                        &ensp;&ensp;transaction_sign = <br />
                        &ensp;&ensp;start_credits = <br />
                        &ensp;&ensp;start_credits_formatted = <br />
                        &ensp;&ensp;end_credits = <br />
                        &ensp;&ensp;end_credits_formatted = <br />
                        ] <br />
                        report_list_transactions_total = [ <br />
                        &ensp;&ensp;number_of_deposits = <br />
                        &ensp;&ensp;number_of_deposits_formatted = <br />
                        &ensp;&ensp;sum_deposits = <br />
                        &ensp;&ensp;sum_deposits_formatted = <br />
                        &ensp;&ensp;no_of_deactivated_tickets = <br />
                        &ensp;&ensp;no_of_deactivated_tickets_formatted = <br />
                        &ensp;&ensp;sum_canceled_deposits = <br />
                        &ensp;&ensp;sum_canceled_deposits_formatted = <br />
                        &ensp;&ensp;no_of_payed_out_tickets = <br />
                        &ensp;&ensp;no_of_payed_out_tickets_formatted = <br />
                        &ensp;&ensp;sum_of_payed_out_tickets = <br />
                        &ensp;&ensp;sum_of_payed_out_tickets_formatted = <br />
                        ] <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-player-money-transactions' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-player-money-transactions' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-player-login-history (created 11-Jun-2018 14:50)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-player-login-history', session_id*, user_id*, start_date (first day of month default), end_date = (current day default)
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.report_login_history(:p_session_id_in, :p_subject_id_in, :p_start_date, :p_end_date)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-player-login-history' <br />
                        session_id = <br />
                        user_id = <br />
                        start_date = <br />
                        end_date = <br />
                        list_report = [ <br />
                        &ensp;&ensp;session_id = <br />
                        &ensp;&ensp;start_date_time = <br />
                        &ensp;&ensp;end_date_time = <br />
                        &ensp;&ensp;duration = <br />
                        &ensp;&ensp;ip_address = <br />
                        &ensp;&ensp;city = <br />
                        &ensp;&ensp;country = <br />
                        ] <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-player-login-history' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-player-login-history' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-player-history (created 28-Aug-2018 15:10, update 13-Sep-2018 9:10, update 02-Oct-2018 09:00)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-player-history', session_id*, player_id*, start_date (first day of month default), end_date = (current day default), page_number = (1 default), number_of_results = (100 default)
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.report_player_history(:p_session_id_in, :p_player_id, :p_start_date, :p_end_date, :p_page_number, :p_number_of_results)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-player-history' <br />
                        session_id = <br />
                        player_id = <br />
                        start_date = <br />
                        end_date = <br />
                        page_number = <br />
                        number_of_results = <br />
                        list_report = [ <br />
                        &ensp;&ensp;transaction_id = <br />
                        &ensp;&ensp;first_name = <br />
                        &ensp;&ensp;last_name = <br />
                        &ensp;&ensp;username = <br />
                        &ensp;&ensp;transaction_type_id = <br />
                        &ensp;&ensp;ticket_status = <br />
                        &ensp;&ensp;amount = <br />
                        &ensp;&ensp;sum_win = <br />
                        &ensp;&ensp;end_credits = <br />
                        &ensp;&ensp;start_credits = <br />
                        &ensp;&ensp;rec_tmstp_formated = <br />
                        &ensp;&ensp;serial_number = <br />
                        &ensp;&ensp;barcode = <br />
                        &ensp;&ensp;currency = <br />
                        &ensp;&ensp;transaction_type = <br />
                        ] <br />
                        total_pages = <br />
                        total_records = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-player-history' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-player-history' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>





        <tr>
            <td>
                <h2>
                PLAYER SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    get-credits (updated: 03-Oct-2018 15:10)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-credits', session_id*, user_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_credits(:p_session_id, :p_subject_id_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'get-credits' <br />
                        session_id = <br />
                        status_out = <br />
                        credits = <br />
                        credits_formatted = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-credits' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-credits' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    create-new-player (created: 30-May-2018 12:50)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'create-new-player', session_id*, username*, password*, email, first_name, last_name, currency*,
                    parent_name, registered_by, language*, address, city, country*, mobile_phone, post_code, address2
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.create_subject(:p_username_in, :p_password_in, :p_first_name_in, :p_last_name_in, :p_currency_in, :p_parent_name_in, :p_registered_by_in, :p_subject_dtype_id_in, :p_player_dtype_in, :p_language_in, :p_email_in, :p_address, :p_city, :p_country, :p_mobile_phone, :p_post_code, :p_commercial_address)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'create-new-player' <br />
                        user_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'create-new-player' <br />
                        error_message = 'GENERAL_ERROR' <br />
                        error_code = 0 <br />
                        error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'create-new-player' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'create-new-player' <br />
                        error_message = 'EMAIL_NOT_AVAILABLE' <br />
                        error_code = 1 <br />
                        error_description = 'Email is not available, already taken in our database' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'create-new-player' <br />
                        error_message = 'USERNAME_NOT_AVAILABLE' <br />
                        error_code = 2 <br />
                        error_description = 'Username is not available, already taken in our database' <br />
                </h4>
            </td>
        </tr>






        <tr>
            <td>
                <h2>
                TERMINAL SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    get-self-service-terminal-name (created: 10-Jul-2018 09:20)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-self-service-terminal-name', mac_address*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'get-self-service-terminal-name' <br />
                        mac_address = <br />
                        terminal_name = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-self-service-terminal-name' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-self-service-terminal-name' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>





        <tr>
            <td>
                <h2>
                TICKET SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    save-ticket-information (updated 12-Jun-2018 13:00, updated 05-Sep-2018 10:30, updated 11-Sep-2018 13:45)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'save-ticket-information', session_id*, user_id*, combination*, number_of_tickets*, sum_bet*, possible_win*, min_possible_win*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.save_ticket(:p_session_id_in, :p_subject_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_possible_win,
                :p_min_possible_win, :cur_draw_id_out, :p_credits_out, :p_serial_number_out, :p_ticket_datetime_out, :p_logged_subject_name_out, :p_barcode_out, :p_language_out, :p_city_out,
                :p_address_out, :p_commercial_address_out, :p_local_jp_code, :p_global_jp_code, :p_payout_expire_time, :p_error_code_out, :p_error_msg_out
                )
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'save-ticket-information' <br />
                        session_id = <br />
                        credits = <br />
                        credits_formatted = <br />
                        serial_number = <br />
                        ticket_datetime = <br />
                        logged_subject_name = <br />
                        barcode = <br />
                        language = <br />
                        city = <br />
                        address = <br />
                        commercial_address = <br />
                        local_jp_code = <br />
                        global_jp_code = <br />
                        payout_expire_time = <br />
                        list_draws = [<br />
                        &ensp;&ensp;'order_num' = <br />
                        ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'save-ticket-information' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-information' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-information' <br />
                    error_message = 'DRAW_MODEL_IS_INACTIVE' <br />
                    error_code = 8 <br />
                    error_description = 'Draw model is inactive' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-information' <br />
                    error_message = 'NEXT_DRAW_IS_NOT_DEFINED' <br />
                    error_code = 9 <br />
                    error_description = 'Next draw is not defined' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-information' <br />
                    error_message = 'UNHANDLED_EXCEPTION' <br />
                    error_code = 10 <br />
                    error_description = 'Unhandled exception. Unknown error occurred in database' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    save-ticket-shift (created 13-09-2018 15:30)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'save-ticket-shift', session_id*, user_id*, combination*, number_of_tickets*, sum_bet*, possible_win*, min_possible_win*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.save_ticket_shift(:p_session_id_in, :p_subject_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_possible_win,
                :p_min_possible_win, :cur_barcodes_out, :p_error_code_out, :p_error_msg_out
                )
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'save-ticket-shift' <br />
                        session_id = <br />
                        list_barcodes = [<br />
                        &ensp;&ensp;'barcode' = <br />
                        ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'save-ticket-shift' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-shift' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-shift' <br />
                    error_message = 'DRAW_MODEL_IS_INACTIVE' <br />
                    error_code = 8 <br />
                    error_description = 'Draw model is inactive' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-shift' <br />
                    error_message = 'NEXT_DRAW_IS_NOT_DEFINED' <br />
                    error_code = 9 <br />
                    error_description = 'Next draw is not defined' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-ticket-shift' <br />
                    error_message = 'UNHANDLED_EXCEPTION' <br />
                    error_code = 10 <br />
                    error_description = 'Unhandled exception. Unknown error occurred in database' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    save-temporary-ticket-information (created: 06-Jun-2018 14:30)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'save-temporary-ticket-information', session_id*, combination*, number_of_tickets*, sum_bet*, jackpot*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.save_temp_ticket(:p_session_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_jackpot_in)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'save-temporary-ticket-information' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'save-temporary-ticket-information' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-temporary-ticket-information' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-temporary-ticket-information' <br />
                    error_message = 'GENERAL_ERROR' <br />
                    error_code = 0 <br />
                    error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'save-temporary-ticket-information' <br />
                    error_message = 'MAXIMUM_TEMPORARY_TICKET_NUMBER' <br />
                    error_code = 7 <br />
                    error_description = 'Can not generate temp ticket. Ticket number has reached allowed maximum.' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    get-ticket-details-from-barcode (created 20-Jul-2018 14:45, updated 11-Sep-2018 08:20, updated 24-Sep-2018 14:30)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-ticket-details-from-barcode', session_id*, ticket_barcode*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_ticket_details_from_barcode(:p_session_id, :p_ticket_barcode)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        status_out = -99, -3, -3 <br />
                        operation = 'get-ticket-details-from-barcode' <br />
                        session_id = <br />
                        ticket_barcode = <br />
                        ticket_result = [<br />
                        &ensp;&ensp;&ensp; payed_out <br />
                        &ensp;&ensp;&ensp; ticket_status <br />
                        &ensp;&ensp;&ensp; order_num <br />
                        &ensp;&ensp;&ensp; rec_tmstp <br />
                        &ensp;&ensp;&ensp; ticket_printed <br />
                        &ensp;&ensp;&ensp; payout_expire_time <br />
                        &ensp;&ensp;&ensp; barcode <br />
                        &ensp;&ensp;&ensp; cashier <br />
                        &ensp;&ensp;&ensp; player_username <br />
                        &ensp;&ensp;&ensp; address <br />
                        &ensp;&ensp;&ensp; city <br />
                        &ensp;&ensp;&ensp; local_jp_code <br />
                        &ensp;&ensp;&ensp; global_jp_code <br />
                        &ensp;&ensp;&ensp; local_win <br />
                        &ensp;&ensp;&ensp; global_win <br />
                        &ensp;&ensp;&ensp; commercial_address <br />
                        &ensp;&ensp;&ensp; language <br />
                        &ensp;&ensp;&ensp; no_of_printings <br />
                        &ensp;&ensp;&ensp; win_paid_out <br />
                        &ensp;&ensp;&ensp; max_possible_win <br />
                        &ensp;&ensp;&ensp; min_possible_win <br />
                        &ensp;&ensp;&ensp; sum_bet <br />
                        &ensp;&ensp;&ensp; payout_expired <br />
                        &ensp;&ensp;&ensp; payout_expired_time <br />
                        &ensp;&ensp;&ensp; payout_timestamp <br />
                        ]<br />
                        combinations_result = [<br />
                        &ensp;&ensp;&ensp; combination_id <br />
                        &ensp;&ensp;&ensp; combination_type <br />
                        &ensp;&ensp;&ensp; combination_value <br />
                        &ensp;&ensp;&ensp; bet <br />
                        &ensp;&ensp;&ensp; win <br />
                        &ensp;&ensp;&ensp; ticket_id <br />
                        &ensp;&ensp;&ensp; order_num <br />
                    ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-ticket-details-from-barcode' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-ticket-details-from-barcode' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    get-last-ticket-for-user (created 20-Jul-2018 14:55)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-last-ticket-for-user', session_id*, user_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_last_ticket_for_subject(:p_session_id, :p_subject_id)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'get-last-ticket-for-user' <br />
                        session_id = <br />
                        user_id = <br />
                        ticket_result = [<br />
                        &ensp;&ensp;&ensp; payed_out <br />
                        &ensp;&ensp;&ensp; ticket_status <br />
                        &ensp;&ensp;&ensp; order_num <br />
                        &ensp;&ensp;&ensp; rec_tmstp <br />
                        &ensp;&ensp;&ensp; ticket_printed <br />
                        &ensp;&ensp;&ensp; payout_expire_time <br />
                        &ensp;&ensp;&ensp; barcode <br />
                        &ensp;&ensp;&ensp; cashier <br />
                        &ensp;&ensp;&ensp; player_username <br />
                        &ensp;&ensp;&ensp; address <br />
                        &ensp;&ensp;&ensp; city <br />
                        &ensp;&ensp;&ensp; local_jp_code <br />
                        &ensp;&ensp;&ensp; global_jp_code <br />
                        &ensp;&ensp;&ensp; local_win <br />
                        &ensp;&ensp;&ensp; global_win <br />
                        &ensp;&ensp;&ensp; commercial_address <br />
                        &ensp;&ensp;&ensp; language <br />
                        &ensp;&ensp;&ensp; no_of_printings <br />
                        &ensp;&ensp;&ensp; win_paid_out <br />
                        &ensp;&ensp;&ensp; max_possible_win <br />
                        &ensp;&ensp;&ensp; min_possible_win <br />
                        &ensp;&ensp;&ensp; sum_bet <br />
                        ]<br />
                        combinations_result = [<br />
                        &ensp;&ensp;&ensp; combination_id <br />
                        &ensp;&ensp;&ensp; combination_type <br />
                        &ensp;&ensp;&ensp; combination_value <br />
                        &ensp;&ensp;&ensp; bet <br />
                        &ensp;&ensp;&ensp; win <br />
                        &ensp;&ensp;&ensp; ticket_id <br />
                        &ensp;&ensp;&ensp; order_num <br />
                    ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-last-ticket-for-user' <br />
                        session_id = <br />
                        user_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-last-ticket-for-user' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    list-previous-draws (created 24-Jul-2018 11:10, updated 25-Jul-2018 13:10)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-previous-draws', session_id*, draw_id*, per_page*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.list_previous_draws(:p_session_id_in, :p_draw_id, :p_number_of_rows)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-previous-draws' <br />
                        session_id = <br />
                        draw_id = <br />
                        per_page = <br />
                        ticket_result = [<br />
                        &ensp;&ensp;&ensp; order_num <br />
                        &ensp;&ensp;&ensp; chosen_number <br />
                        &ensp;&ensp;&ensp; stars <br />
                        &ensp;&ensp;&ensp; global_jackpot_code <br />
                        &ensp;&ensp;&ensp; local_jackpot_code <br />
                        &ensp;&ensp;&ensp; draw_id <br />
                    ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-previous-draws' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-previous-draws' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    cancel-ticket (created: 30-Jul-2018 09:22)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'cancel-ticket', session_id*, barcode*, cashier_pincode*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.cancel_ticket(:p_barcode, :p_session_id_in, :p_pincode_in, :p_status_out)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'cancel-ticket' <br />
                        session_id = <br />
                        barcode = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'cancel-ticket' <br />
                        session_id = <br />
                        barcode = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'cancel-ticket' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'cancel-ticket' <br />
                    session_id = <br />
                    barcode = <br />
                    error_message = 'GENERAL_ERROR' <br />
                    error_code = 0 <br />
                    error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    print-ticket (created: 31-Jul-2018 08:40)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'print-ticket', session_id*, ticket_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.print_ticket(:p_ticket_id_in, :p_session_id, :p_status_out)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'print-ticket' <br />
                        session_id = <br />
                        ticket_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'print-ticket' <br />
                        session_id = <br />
                        ticket_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'print-ticket' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'print-ticket' <br />
                    session_id = <br />
                    ticket_id = <br />
                    error_message = 'GENERAL_ERROR' <br />
                    error_code = 0 <br />
                    error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    update-status-ticket-win (created: 17-Aug-2018 16:04, updated: 05-Sep-2018 10:30 (official error code PAYOUT_TIME_EXPIRED), updated: 02-Oct-2018 11:40 (session_id parameter))
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'update-status-ticket-win', session_id*, ticket_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.update_status_ticket_win(:p_session_id, :p_ticket_id_in, :p_ticket_status_in = 4)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'update-status-ticket-win' <br />
                        session_id = <br />
                        ticket_id = <br />
                        true_status = 1 | -4<br />
                        message = Successful | Payout time expired | Unsuccessful <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'update-status-ticket-win' <br />
                        session_id = <br />
                        ticket_id = <br />
                        true_status = 1 | -4 <br />
                        message = Successful | Payout time expired | Unsuccessful <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'update-status-ticket-win' <br />
                        session_id = <br />
                        ticket_id = <br />
                        true_status = 1 | -4 <br />
                        message = Successful | Payout time expired | Unsuccessful <br />
                        error_message = PAYOUT_TIME_EXPIRED <br />
                        error_code = 11 <br />
                        error_description = Payout time expired <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'update-status-ticket-win' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h2>
                ADMINISTRATION SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-affiliates-and-parameters (created 12-Jun-2018 14:50)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-affiliates-and-parameters', session_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.list_aff_and_parameters(:p_session_id_in =, cur_result_out, p_status_out)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-affiliates-and-parameters' <br />
                        session_id = <br />
                        list_report = [ <br />
                        &ensp;&ensp;'parameter_id' = <br />
                        &ensp;&ensp;'affiliate_id' = <br />
                        &ensp;&ensp;'currency' = <br />
                        &ensp;&ensp;'value' = <br />
                        &ensp;&ensp;'affiliate_parameter_value_id' = <br />
                        &ensp;&ensp;'parameter_name' = <br />
                        ]
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-affiliates-and-parameters' <br />
                        session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-affiliates-and-parameters' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h2>
                SHIFT-CASHIER SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-collector-shift-report (created 23-Jul-2018 09:15)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-collector-shift-report', session_id*, collector_id*, cashier_id*, service_code*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.collectors_shift_report(:p_session_id_in, :p_collector_id, :p_cashier_id, :p_service_code, :p_collector_name, :p_last_collect_tmstp, :cur_result_out)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-collector-shift-report' <br />
                        session_id = <br />
                        collector_id = <br />
                        cashier_id = <br />
                        service_code = <br />
                        report = [ <br />
                        &ensp;&ensp;shift_number = <br />
                        &ensp;&ensp;total =  <br />
                        &ensp;&ensp;total_formatted = <br />
                        &ensp;&ensp;end_balance = <br />
                        &ensp;&ensp;end_balance_formatted = <br />
                        ] <br />
                        collector_name = <br />
                        last_collect_date_time = <br />
                        <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-collector-shift-report' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-collector-shift-report' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    list-cashier-shift-report (created 23-Jul-2018 09:25)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'list-cashier-shift-report', session_id*, cashier_id*, shift_number*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.cashier_shift_report(:p_session_id_in, :p_cashier_id, :p_shift_no, :cur_report_list)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'list-cashier-shift-report' <br />
                        session_id = <br />
                        cashier_id = <br />
                        shift_number = <br />
                        report = [ <br />
                        &ensp;&ensp;shift_number = <br />
                        &ensp;&ensp;start_balance =  <br />
                        &ensp;&ensp;start_balance_formatted = <br />
                        &ensp;&ensp;end_balance = <br />
                        &ensp;&ensp;end_balance_formatted = <br />
                        &ensp;&ensp;shift_start_time = <br />
                        &ensp;&ensp;shift_end_time = <br />
                        &ensp;&ensp;sum_deposit = <br />
                        &ensp;&ensp;sum_deposit_formatted = <br />
                        &ensp;&ensp;sum_payout = <br />
                        &ensp;&ensp;sum_payout_formatted = <br />
                        &ensp;&ensp;sum_storno = <br />
                        &ensp;&ensp;sum_storno_formatted = <br />
                        ] <br />
                        <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'list-cashier-shift-report' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'list-cashier-shift-report' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    collect-from-cashier (created 23-Jul-2018 10:10)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'collect-from-cashier', session_id*, cashier_id*, collector_id*, amount*, service_code*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.collect_from_cashier(:p_session_id_in, :p_cashier_id, :p_collector_id, :p_amount, :p_service_code)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'collect-from-cashier' <br />
                        session_id = <br />
                        cashier_id = <br />
                        collector_id = <br />
                        amount = <br />
                        service_code = <br />
                        <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'collect-from-cashier' <br />
                        session_id = <br />
                        cashier_id = <br />
                        collector_id = <br />
                        amount = <br />
                        service_code = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'collect-from-cashier' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'collect-from-cashier' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>

        <tr>
            <td>
                <h2>
                    SESSION SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    get-game-draw-session-id (created 09-Oct-2018 09:10)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-game-draw-session-id', session_id*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.get_game_draw_session(:p_session_id)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'get-game-draw-session-id' <br />
                        session_id = <br />
                        game_draw_session_id = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-game-draw-session-id' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-game-draw-session-id' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h2>
                    INTEGRATION SERVICE
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color: red;">
                    integrate-player-hierarchy (created 31-Oct-2018 09:30, updated 31-Oct-2018 10:50, updated 31-Oct-2018 14:15)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'integrate-player-hierarchy', username*, subject_path*, currency*, mac_address, player_type, ip_address, login_platform, credits
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.core.integrate_player_hierarchy(:p_username_in, :p_subject_path, :p_currency_in, :p_mac_address, :p_player_type, :p_ip_address, :p_login_platform, :p_credits)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'integrate-player-hierarchy' <br />
                        status_out = <br />
                        session_id_out = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'integrate-player-hierarchy' <br />
                        error_message = 'GENERAL_ERROR' <br />
                        error_code = 0 <br />
                        error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'integrate-player-hierarchy' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    post-integration-transaction (created 31-Oct-2018 10:20)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'post-integration-transaction', player_id*, amount*, game_id*, transaction_type_id, game_session_id
                </h4>
                <br />
                TARGETED INTEGRATION URL(s):
                <br />
                http://www.best200.com/onlinecasinoservice_dev/lucky-game-integration/post-transaction?player_id=&amount=&game_id=&transaction_type_id=&game_session_id=
                <br />
                username/password: active/studio
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'post-integration-transaction' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'post-integration-transaction' <br />
                        error_message = 'GENERAL_ERROR' <br />
                        error_code = 0 <br />
                        error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'post-integration-transaction' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>



        <tr>
            <td>
                <h3 style="color: red;">
                    set-integration-credits (created 31-Oct-2018 14:30)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'set-integration-credits', username*, credits*
                </h4>
                <br />
                Database procedures:
                <br />
                tomboladb.tombola.set_credits(:p_username, :p_credits)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'set-integration-credits' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'set-integration-credits' <br />
                        error_message = 'GENERAL_ERROR' <br />
                        error_code = 0 <br />
                        error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'set-integration-credits' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>


        <tr>
            <td>
                <h3 style="color: red;">
                    get-pending-wins-for-integration (created 02-Nov-2018 09:50)
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    Parameters: operation = 'get-pending-wins-for-integration', player_id*
                </h4>
                <br />
                Database procedures:
                <br />
                core.get_pending_wins(:p_player_id)
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="color: blue;">
                    OK Response: <br /><br />
                        status = 'OK' <br />
                        operation = 'get-pending-wins-for-integration' <br />
                        sum_win = <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                    NOK Response: <br /><br />
                        status = 'NOK' <br />
                        operation = 'get-pending-wins-for-integration' <br />
                        error_message = 'GENERAL_ERROR' <br />
                        error_code = 0 <br />
                        error_description = 'Unknown error occurred' <br />
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>
                NOK Response: <br /><br />
                    status = 'NOK' <br />
                    operation = 'get-pending-wins-for-integration' <br />
                    error_message = 'MISSING_PARAMETERS' <br />
                    error_code = 6 <br />
                    error_description = 'One or more parameters are missing' <br />
                </h4>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
