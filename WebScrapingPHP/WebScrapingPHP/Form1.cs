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

namespace WebScrapingPHP
{
    public partial class Form1 : Form
    {
        public string simulation_stats = "stop";
        public int waiting_weather, waiting_dust, waiting_emergency;

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            callweather();
            //var client = new WebClient();

            //client.Headers.Add("user-agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.0.3705;)");

            //Stream data = client.OpenRead("Hello world");
            //StreamReader reader = new StreamReader(data);
            //string s = reader.ReadToEnd();
            //Console.WriteLine(s);
            //data.Close();
            //reader.Close();
        }

        private void button_dust_Click(object sender, EventArgs e)
        {
            calldust();
        }

        private void _emergency_Click(object sender, EventArgs e)
        {
            callemergency();
        }

        private void button_start_Click(object sender, EventArgs e)
        {
            simulation_stats = "start";
            waiting_dust = Int32.Parse(textBox_dust.Text);
            waiting_emergency = Int32.Parse(textBox_emergency.Text);
            waiting_weather = Int32.Parse(textBox_weather.Text);

            //simulate();
        }

        private void callemergency()
        {
            main_box.Text = "Start collecting Emergency Data~\n";
            var webClient = new WebClient();
            string reply = webClient.DownloadString("http://localhost/rcms_web/gather_emergency_only.php");

            main_box.AppendText(reply);
        }

        private void calldust()
        {
            main_box.Text = "Start collecting Dust Data~\n";
            var webClient = new WebClient();
            string reply = webClient.DownloadString("http://localhost/rcms_web/gather_dust_only.php");

            main_box.AppendText(reply);
        }

        private void callweather()
        {
            main_box.Text = "Start collecting Weather Data~\n";

            var webClient = new WebClient();
            string reply = webClient.DownloadString("http://localhost/rcms_web/gather_weather_only.php");

            main_box.AppendText(reply);
        }

        private void button_stop_Click(object sender, EventArgs e)
        {
            simulation_stats = "stop";
        }

        //private async void simulate_weather(object sender, RoutedEventArgs e)
        //{
        //    while(simulation_stats != "stop")
        //    {
        //        label1.Text = "Test";
        //        await Task.Delay(waiting_weather);
        //        label1.Text = "";
        //    }
        //}
    }
}
