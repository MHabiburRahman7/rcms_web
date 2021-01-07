using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Net;
using System.IO;

using System.Xml;
using System.Xml.Linq;
using MySql.Data.MySqlClient;

namespace WebScrapingPHP
{
    public partial class Form1 : Form
    {
        public string simulation_stats = "stop";
        public int waiting_weather, waiting_dust, waiting_emergency;
        public string key = "5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&";

        public List<int> dust_mins = new List<int>();
        public List<int> weather_mins = new List<int>();

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            //new method
            downloadWeather();
        }

        private bool timeToDownload(List<int> minutes)
        {
            int curr_min = Int32.Parse(DateTime.Now.ToString("mm"));

            foreach (var item in minutes)
            {
                if (item == curr_min)
                    return true;
            }

            return false;
        }

        private void button_dust_Click(object sender, EventArgs e)
        {
            //new function full from c#
            downloadDust();
        }

        private void _emergency_Click(object sender, EventArgs e)
        {
            //new method
            downloadEmergencyData();
        }

        private async void button_start_Click(object sender, EventArgs e)
        {
            simulation_stats = "start";

            //weather and dust is based on minutes, can be more than one min
            List<string> temp = new List<string>();
            if (textBox_dust.Text != "")
            {
                temp = textBox_dust.Text.Split(',').ToList();
                foreach (var item in temp)
                {
                    dust_mins.Add(Int32.Parse(item));
                }
                temp = new List<string>();
            }

            if (textBox_weather.Text != "")
            {
                temp = textBox_weather.Text.Split(',').ToList();
                foreach (var item in temp)
                {
                    weather_mins.Add(Int32.Parse(item));
                }
                temp = new List<string>();
            }

            //emergency is based on second
            if(textBox_emergency.Text != null)
            {
                waiting_emergency = Int32.Parse(textBox_emergency.Text) * 1000;
            }

            //1 min delay
            waiting_dust = 61000;
            waiting_weather = 61000;

            //simulate();
            startSimulation();
        }

        public async Task<string> RunAsync()
        {
            await Task.Delay(1000);
            return "Finished";
        }

        private async void button_stop_Click(object sender, EventArgs e)
        {

            simulation_stats = "stop";

            //0 min delay
            //0 min delay
            waiting_dust = 0;
            waiting_weather = 0;
        }

        private async void startSimulation()
        {
            while (simulation_stats == "start")
            {
                int res = 0;

                bool time_to_download_weather = false, time_to_download_dust = false;
                //validation
                if (timeToDownload(dust_mins))
                    time_to_download_dust = true;
                if (timeToDownload(weather_mins))
                    time_to_download_weather = true;

                //downloading
                if (time_to_download_dust)
                {
                    string timeLog = "[" + DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss") + "]\n";
                    main_box.AppendText(timeLog);
                    Console.WriteLine(timeLog);

                    res = await downloadDust();


                    if (res == 1)
                        main_box.AppendText("Await done, start to check dust data\n");
                }

                //
                if (time_to_download_weather)
                {
                    string timeLog = "[" + DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss") + "]\n";
                    main_box.AppendText(timeLog);
                    Console.WriteLine(timeLog);

                    res = await downloadWeather();

                    if (res == 1)
                        main_box.AppendText("Await done, start to check weather data\n");
                }
                                
                int emergencyRes = await downloadEmergencyData();
                if (emergencyRes == 1)
                    main_box.AppendText("Start checking emergency data\n");
            }
        }

        private bool insertEmergencyData(List<string> message_inp)
        {
            string connectionString;
            connectionString = "datasource=127.0.0.1;port=3306;username=root;password=;database=rcms_new;";
            string query = "SELECT * FROM `tbl_emergency_data` WHERE `GEN_YMDHMS` = '"+message_inp[0]+"';";

            MySqlConnection dbconn = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand(query, dbconn);

            try
            {
                dbconn.Open();
                var rdr = command.ExecuteReader();
                
                while (rdr.Read())
                {
                    var temp = rdr.GetString(0);
                    //data is already exist
                    dbconn.Close();
                    return false;
                    
                }
            }
            catch (Exception e)
            {
                
            }

            dbconn.Close();
            //data not exist
            query = "INSERT INTO `tbl_emergency_data`(`EMERGENCY_CODE`, `SD`, `SGG`, `EMERGENCY_TEXT`, `GEN_YMDHMS`) VALUES('001', '" + message_inp[1] + "', '" + message_inp[2] + "', '" + message_inp[3] + "', '" + message_inp[0] + "')";
            command = new MySqlCommand(query, dbconn);

            dbconn.Open();
            command.ExecuteReader();
            dbconn.Close();

            return true;
        }

        private async Task<int> downloadEmergencyData()
        {
            string timeLog = "[" + DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss") + "]\n";
            main_box.AppendText(timeLog);
            Console.WriteLine(timeLog);

            main_box.AppendText("Collecting Emergency Messages Data~\n");
            Console.WriteLine("Collecting Emergency Messages Data");

            //get data
            string URLString = "http://apis.data.go.kr/1741000/DisasterMsg2/getDisasterMsgList?ServiceKey="+key+"type=xml&pageNo=1&numOfRows=10&flag=Y";

            XmlTextReader reader = new XmlTextReader(URLString);

            //created date - location phase 1(sd) - location phase 2(sgg) - message text
            List<List<string>> collected_msg = new List<List<string>>();
            List<string> temp = new List<string>();
            bool isLocation = false, isDate = false, isMsg = false;
            while (reader.Read())
            {
                switch (reader.NodeType)
                {
                    case XmlNodeType.Element:
                        if (reader.Name == "create_date")
                            isDate = true;
                        else if (reader.Name == "location_name")
                            isLocation = true;
                        else if (reader.Name == "msg")
                            isMsg = true;

                        break;
                    
                    case XmlNodeType.Text:
                        if (isDate)
                        {
                            temp.Add(reader.Value.ToString());
                            isDate = false;
                        }
                        if (isLocation)
                        {
                            string[] smaller_temp = reader.Value.ToString().Split(' ');
                            if(smaller_temp.Count<string>() > 1) {
                                temp.Add(smaller_temp[0]);
                                temp.Add(smaller_temp[1]);

                            }
                            else
                            {
                                temp.Add(smaller_temp[0]);
                                temp.Add(smaller_temp[0]);
                            }
                            isLocation = false;
                        }
                        if (isMsg)
                        {
                            temp.Add(reader.Value.ToString());
                            isMsg = false;

                            collected_msg.Add(temp);
                            temp = new List<string>();
                        }
                        break;
                    
                }
            }

            //insert data + count
            int count = 0;
            bool result_each = false;
            foreach (var item in collected_msg)
            {
                result_each = insertEmergencyData(item);
                if (result_each)
                    count++;
            }

            //x data is updated
            main_box.AppendText(count+" number of Emergency Messages Data updated\n");
            Console.WriteLine(count+" number of Emergency Messages Data updated");

            await Task.Delay(waiting_emergency);
            return 1;
        }

        private List<string> getWeatherDateTime()
        {
            //validify with the existing data on api
            string year = DateTime.Now.ToString("yyyy");
            string month = DateTime.Now.ToString("MM");
            string date = DateTime.Now.ToString("dd");
            string date_inp = year+month+date;
            string hr = DateTime.Now.ToString("HH");
            string time_inp = hr+"00";
            DateTime workingDateTime = new DateTime(Int32.Parse(year), Int32.Parse(month), Int32.Parse(date), Int32.Parse(hr), 0,0,0);

            
            String URLString = "http://apis.data.go.kr/1360000/VilageFcstInfoService/getUltraSrtNcst?serviceKey=" + key + "&numOfRows=10&pageNo=1&base_date=" + date_inp + "&base_time=" + time_inp + "&nx=60&ny=127";
            XmlTextReader reader = new XmlTextReader(URLString);

            //Hum is REH || temp is T1H
            bool usePrevHour = false, checkResponse = false;

            List<List<string>> res = new List<List<string>>();
            List<string> temp = new List<string>();

            //REH = itt -> 1 || T1H = itt -> 3
            while (reader.Read())
            {
                switch (reader.NodeType)
                {
                    case XmlNodeType.Element:
                        if(reader.Name == "resultCode")
                        {
                            checkResponse = true;
                        }
                        break;
                    case XmlNodeType.Text:
                        if (checkResponse)
                        {
                            if (reader.Value.ToString() == "00")
                                usePrevHour = false;
                            else
                                usePrevHour = true;
                            checkResponse = false;
                        }
                        break;
                    
                    default:
                        break;
                }
            }

            if (usePrevHour)
            {
                workingDateTime = workingDateTime.AddHours(-1);
            }

            //sql format - date - time now - time prev
            List<string> finalRes = new List<string>();
            //string temporary = DateTime.Now.ToString("yyyy-MM-dd HH:00");
            finalRes.Add(workingDateTime.ToString("yyyy-MM-dd HH:00"));

            //temp = DateTime.Now.ToString("yyyyMMdd");
            finalRes.Add(workingDateTime.ToString("yyyyMMdd"));

            //temp = DateTime.Now.ToString("HH00");
            finalRes.Add(workingDateTime.ToString("HH00"));

            //temp = (DateTime.Now.Hour - 1).ToString()+"00";
           // res.Add(temp);

            return finalRes;
        }

        private void insertWeatherData(string gridx, string gridy, string time_sql_format, List<string> hum_n_temp )
        {
            //get location id list
            string connectionString;
            connectionString = "datasource=127.0.0.1;port=3306;username=root;password=;database=rcms_new;";
            string query = "SELECT DISTINCT `IDX` FROM `tbl_full_location` WHERE Grid_X='"+gridx+"' AND Grid_y='"+gridy+"';";

            MySqlConnection dbconn = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand(query, dbconn);

            dbconn.Open();
            List<string> loc_idx = new List<string>();
            var rdr = command.ExecuteReader();
            while (rdr.Read())
            {
                loc_idx.Add(rdr.GetString(0));
            }
            dbconn.Close();

            //insert to db
            foreach (var item in loc_idx)
            {
                query = "INSERT INTO `tbl_wet_data`(`IDX_DATETIME`, `DATA_GUBUN`, `LOCATION`, `VALUE_1`, `VALUE_2`) VALUES('"+time_sql_format+"', 'T,H', '"+item+"', '"+hum_n_temp[0]+"', '"+hum_n_temp[1]+"')";
                command = new MySqlCommand(query, dbconn);

                dbconn.Open();
                command.ExecuteReader();
                dbconn.Close();
            }
        }

        private List<string> getSingleGridWeatherData(string gridx, string gridy, string date_inp, string time_inp)
        {
            //String URLString = "http://apis.data.go.kr/1360000/VilageFcstInfoService/getUltraSrtNcst?serviceKey="+key+"&numOfRows=10&pageNo=1&base_date="+date_inp+"&base_time="+time_inp+"&nx="+gridx+"&ny="+gridy+"";
            String URLString = "http://apis.data.go.kr/1360000/VilageFcstInfoService/getUltraSrtNcst?serviceKey=" + key + "&numOfRows=10&pageNo=1&base_date=" + date_inp + "&base_time=" + time_inp + "&nx=" + gridx + "&ny=" + gridy + "";
            XmlTextReader reader = new XmlTextReader(URLString);

            //Hum is REH || temp is T1H
            bool checkVal = false, checkCode = false;
            //bool prevHour = false;

            List<List<string>> res = new List<List<string>>();
            List<string> temp = new List<string>();


            int itt = 0;
            checkVal = false;
            //REH = itt -> 1 || T1H = itt -> 3
            while (reader.Read())
            {
                switch (reader.NodeType)
                {
                    case XmlNodeType.Element:
                        if(reader.Name == "item")
                        {
                            itt++;
                        }
                        if(reader.Name == "obsrValue" && (itt == 2 ||itt == 4))
                        {
                            checkVal = true;
                        }
                        if(reader.Name == "resultCode")
                        {
                            checkCode = true;
                        }
                        break;
                    case XmlNodeType.Text:
                        if (checkCode)
                        {
                            //if (reader.Value.ToString() != "00")
                            //{
                            //    prevHour = true;
                            //}
                            checkCode = false;
                        }
                        if (checkVal)
                        {
                            temp.Add(reader.Value.ToString());
                            checkVal = false;
                        }

                        if(itt == 4)
                        {
                            if(res.Count() == 0)
                                res.Add(temp);
                        }

                        break;
                }
            }

            if(res.Count() > 0)
                return res[0];
            else
            {
                temp.Add("");
                res.Add(temp);
                return res[0];
            }
                

            return res[0];
        }

        private async Task<int> downloadWeather()
        {
            main_box.AppendText("Collecting Weather Data~\n");
            Console.WriteLine("Collecting Weather Data");

            //get lat long
            List<List<string>> x_y_list = new List<List<string>>();
            List<string> temp = new List<string>();

            string connectionString;
            connectionString = "datasource=127.0.0.1;port=3306;username=root;password=;database=rcms_new;";
            string query = "SELECT DISTINCT `Grid_X`, `Grid_Y` FROM `tbl_full_location`";

            MySqlConnection dbconn = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand(query, dbconn);

            //command.CommandTimeout = 60;

            dbconn.Open();
            var rdr = command.ExecuteReader();
            while (rdr.Read())
            {
                temp.Add(rdr.GetString(0));
                temp.Add(rdr.GetString(1));

                x_y_list.Add(temp);
                temp = new List<string>();
            }
            dbconn.Close();

            //need update?
            bool needUpdate = true;
            List<string> curr_date = getWeatherDateTime();
            query = "SELECT `IDX` FROM `tbl_wet_data` WHERE `IDX_DATETIME` = '"+curr_date[0]+"'";
            command = new MySqlCommand(query, dbconn);

            dbconn.Open();
            rdr = command.ExecuteReader();
            //string temp_str;
            while (rdr.Read())
            {
                //temp_str = rdr.GetString(0);
                needUpdate = false;
                break;
            }
            dbconn.Close();

            if (needUpdate)
            {
                int count = 0;
                bool shouldPrint = true;
                foreach (var item in x_y_list)
                {
                    //getData for each lat long
                    List<string> temp_data = getSingleGridWeatherData(item[0], item[1], curr_date[1], curr_date[2]);
                    //insert
                    if(temp_data.Count() > 1)
                        insertWeatherData(item[0], item[1], curr_date[0], temp_data);

                    if(count %400 == 0 && shouldPrint)
                    {
                        Console.WriteLine("Collecting " + (count*100)/x_y_list.Count() + "% data . . .");
                        shouldPrint = false;
                    }else if(count % 401 == 0)
                    {
                        shouldPrint = true;
                    }
                    count++;
                }

                main_box.AppendText("Collecting data from time " + curr_date[0] + " is Done\n");
                Console.WriteLine("Collecting data from time " + curr_date[0] + " is Done");
            }
            else
            {
                main_box.AppendText("Data from " + curr_date[0] + " is Already exist\n");
                Console.WriteLine("Data from " + curr_date[0] + " is Already exist");
            }

            await Task.Delay(waiting_weather);
            return 1;
        }

        private void button_show_Click(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("http://127.0.0.1/phpmyadmin");
        }

        public List<List<string>> downloadTheDust(string inp)
        {
            //String URLString = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=" + inp + "&pageNo=1&numOfRows=200&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&ver=1.3";
            String URLString = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=" + inp + "&pageNo=1&numOfRows=200&ServiceKey="+key+"&ver=1.3";
            XmlTextReader reader = new XmlTextReader(URLString);

            bool isStationName = false, isDataTime = false, isPm10 = false;

            List<List<string>> res = new List<List<string>>();
            List<string> temp = new List<string>();
            while (reader.Read())
            {
                switch (reader.NodeType)
                {
                    case XmlNodeType.Element: // The node is an element.

                        if (reader.Name == "stationName")
                        {
                            //Console.Write("<" + reader.Name);

                            //while (reader.MoveToNextAttribute()) // Read the attributes.
                            //    Console.Write(" " + reader.Name + "='" + reader.Value + "'");
                            //Console.Write(">");
                            //Console.WriteLine(">");
                            isStationName = true;
                        }else if(reader.Name == "dataTime")
                        {
                            isDataTime = true;
                        }
                        else if (reader.Name == "pm10Value")
                        {
                            isPm10 = true;
                        }

                        break;
                    case XmlNodeType.Text: //Display the text in each element.
                        if (isStationName)
                        {
                            temp.Add(reader.Value.ToString());
                            //Console.WriteLine(reader.Value);
                            isStationName = false;
                        }else if (isDataTime)
                        {
                            if (reader.Value.ToString().Contains("24:00"))
                            {
                                string[] dateandtime = reader.Value.ToString().Split(' ');

                                string[] date = dateandtime[0].Split('-');
                                string[] time = dateandtime[1].Split(':');

                                //convert to epoch
                                DateTime start = new DateTime(Int32.Parse(date[0]), Int32.Parse(date[1]), Int32.Parse(date[2]), 0, 0, 0, 0);

                                string s = start.AddDays(+1).ToString("yyyy-MM-dd HH:mm");

                                temp.Add(s);
                            }
                            else
                                temp.Add(reader.Value.ToString());
                            //Console.WriteLine(reader.Value);
                            isDataTime = false;
                        }else if (isPm10)
                        {
                            temp.Add(reader.Value.ToString());
                            res.Add(temp);
                            temp = new List<string>();
                            isPm10 = false;
                        }
                        break;
                }
            }


            return res;
        }

        private async Task<int> downloadDust()
        {
            main_box.AppendText("Collecting Dust Data~\n");
            Console.WriteLine("Collecting Dust Data");

            //get the location list
            string connectionString;
            connectionString = "datasource=127.0.0.1;port=3306;username=root;password=;database=rcms_new;";
            string query = "SELECT `NAME_HGL`, `NAME_DUST` FROM `province`";

            MySqlConnection dbconn = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand(query, dbconn);

            command.CommandTimeout = 60;

            dbconn.Open();
            var rdr = command.ExecuteReader();

            //downloadTheDust("hello");


            //download the data from api
            //collection of all list - collection of every province - collection of every station name (station name, date time, pm10 value)
            List<List<List<string>>> finalRes = new List<List<List<string>>>();
            List<string> realProvinceName = new List<string>();
            //List<List<string>> temp = new List<List<string>>();
            while (rdr.Read())
            {
                //main_box.AppendText(rdr.GetString(0) + " - " +
                //        rdr.GetString(1) + "\n");

                //Console.WriteLine("{0} {1} ", rdr.GetString(0),
                //        rdr.GetString(1));

                //temp = downloadTheDust(rdr.GetString(1));
                realProvinceName.Add(rdr.GetString(0));
                finalRes.Add(downloadTheDust(rdr.GetString(1)));
                //temp = new List<List<string>>();
            }
            dbconn.Close();

            //do we need to insert?
            query = "SELECT `IDX` FROM `tbl_dust_data` WHERE `IDX_DATETIME` = '"+ finalRes[0][0][1] + "'"; ;
            command = new MySqlCommand(query, dbconn);

            dbconn.Open();
            rdr = command.ExecuteReader();
            bool dataExist = false;
            string temp;
            while (rdr.Read())
            {
                temp = rdr.GetString(0);
                //exist so no need to update
                dataExist = true;
                break;
            }
            if (dataExist == true)
            {
                main_box.AppendText("Data of " + finalRes[0][0][1] + " already exist\n");
                Console.WriteLine("Data of " + finalRes[0][0][1] + " already exist");
                return 1;
            }

            main_box.AppendText("Downloaded data from " + finalRes[0][0][1] + " and inserting to DB\n");
            Console.WriteLine("Downloaded data from " + finalRes[0][0][1] + " and inserting to DB");
            dbconn.Close();

            List<string> testArr = new List<string>();
            //insert into db
            for (int i=0; i<realProvinceName.Count(); i++)
            {
                for(int j=0; j<finalRes[i].Count(); j++)
                {
                    //validation
                    query = "SELECT `IDX` FROM `tbl_full_location` WHERE `Phase_2` LIKE '%"+ finalRes[i][j][0] + "%'";
                    command = new MySqlCommand(query, dbconn);

                    //command.CommandTimeout = 60;
                    

                    List<string> idx = new List<string>();

                    try
                    {
                        dbconn.Open();
                        rdr = command.ExecuteReader();
                        while (rdr.Read())
                        {
                            var local_id = rdr.GetString(0);
                            testArr.Add(local_id);
                            if(testArr.Count() != testArr.Distinct().Count())
                            {
                                //contain duplicate
                                testArr.RemoveAt(testArr.Count() - 1);
                            }
                            else
                            {
                                idx.Add(rdr.GetString(0));
                            }
                        }

                        dbconn.Close();
                    }
                    catch (Exception e)
                    {
                        dbconn.Close();
                        continue;
                    }

                    for(int k=0; k<idx.Count(); k++)
                    {
                        query = "INSERT INTO `tbl_dust_data`(`IDX_DATETIME`, `ITEMCODE`, `LOCATION`, `VALUE_1`) VALUES('" + finalRes[i][j][1] + "', 'PM10', '" + idx[k] + "', '" + finalRes[i][j][2] + "')";
                        command = new MySqlCommand(query, dbconn);
                        //command.CommandTimeout = 60;
                        dbconn.Open();
                        command.ExecuteReader();
                        dbconn.Close();
                    }
                }
            }

            dbconn.Close();

            //clean the db of location 2147483647
            query = "DELETE FROM `tbl_dust_data` WHERE `LOCATION`='2147483647'";
            command = new MySqlCommand(query, dbconn);
            //command.CommandTimeout = 60;
            dbconn.Open();
            command.ExecuteReader();
            dbconn.Close();

            main_box.AppendText("Insertion data on " + finalRes[0][0][1] + " Is Done \n");
            Console.WriteLine("Insertion data on " + finalRes[0][0][1] + " Is Done");

            await Task.Delay(waiting_dust);
            return 1;
        }

        private void main_box_TextChanged(object sender, EventArgs e)
        {
            // set the current caret position to the end
            main_box.SelectionStart = main_box.Text.Length;
            // scroll it automatically
            main_box.ScrollToCaret();
        }

    }
}
