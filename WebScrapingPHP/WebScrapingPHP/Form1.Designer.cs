namespace WebScrapingPHP
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.button1 = new System.Windows.Forms.Button();
            this.main_box = new System.Windows.Forms.RichTextBox();
            this.button_dust = new System.Windows.Forms.Button();
            this._emergency = new System.Windows.Forms.Button();
            this.button_start = new System.Windows.Forms.Button();
            this.button_show = new System.Windows.Forms.Button();
            this.textBox_weather = new System.Windows.Forms.TextBox();
            this.textBox_dust = new System.Windows.Forms.TextBox();
            this.textBox_emergency = new System.Windows.Forms.TextBox();
            this.button2 = new System.Windows.Forms.Button();
            this.button_stop = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(12, 20);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(105, 35);
            this.button1.TabIndex = 0;
            this.button1.Text = "get weather";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // main_box
            // 
            this.main_box.Location = new System.Drawing.Point(211, 20);
            this.main_box.Name = "main_box";
            this.main_box.Size = new System.Drawing.Size(327, 362);
            this.main_box.TabIndex = 1;
            this.main_box.Text = "";
            // 
            // button_dust
            // 
            this.button_dust.Location = new System.Drawing.Point(12, 61);
            this.button_dust.Name = "button_dust";
            this.button_dust.Size = new System.Drawing.Size(105, 35);
            this.button_dust.TabIndex = 2;
            this.button_dust.Text = "get dust";
            this.button_dust.UseVisualStyleBackColor = true;
            this.button_dust.Click += new System.EventHandler(this.button_dust_Click);
            // 
            // _emergency
            // 
            this._emergency.Location = new System.Drawing.Point(12, 102);
            this._emergency.Name = "_emergency";
            this._emergency.Size = new System.Drawing.Size(105, 35);
            this._emergency.TabIndex = 3;
            this._emergency.Text = "get emergency";
            this._emergency.UseVisualStyleBackColor = true;
            this._emergency.Click += new System.EventHandler(this._emergency_Click);
            // 
            // button_start
            // 
            this.button_start.Location = new System.Drawing.Point(12, 143);
            this.button_start.Name = "button_start";
            this.button_start.Size = new System.Drawing.Size(193, 35);
            this.button_start.TabIndex = 4;
            this.button_start.Text = "Start Async";
            this.button_start.UseVisualStyleBackColor = true;
            this.button_start.Click += new System.EventHandler(this.button_start_Click);
            // 
            // button_show
            // 
            this.button_show.Location = new System.Drawing.Point(12, 223);
            this.button_show.Name = "button_show";
            this.button_show.Size = new System.Drawing.Size(193, 35);
            this.button_show.TabIndex = 5;
            this.button_show.Text = "Show Data";
            this.button_show.UseVisualStyleBackColor = true;
            // 
            // textBox_weather
            // 
            this.textBox_weather.Location = new System.Drawing.Point(123, 28);
            this.textBox_weather.Name = "textBox_weather";
            this.textBox_weather.Size = new System.Drawing.Size(82, 21);
            this.textBox_weather.TabIndex = 6;
            // 
            // textBox_dust
            // 
            this.textBox_dust.Location = new System.Drawing.Point(123, 69);
            this.textBox_dust.Name = "textBox_dust";
            this.textBox_dust.Size = new System.Drawing.Size(82, 21);
            this.textBox_dust.TabIndex = 7;
            // 
            // textBox_emergency
            // 
            this.textBox_emergency.Location = new System.Drawing.Point(123, 110);
            this.textBox_emergency.Name = "textBox_emergency";
            this.textBox_emergency.Size = new System.Drawing.Size(82, 21);
            this.textBox_emergency.TabIndex = 8;
            // 
            // button2
            // 
            this.button2.Location = new System.Drawing.Point(12, 347);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(193, 35);
            this.button2.TabIndex = 9;
            this.button2.Text = "Check Connection";
            this.button2.UseVisualStyleBackColor = true;
            // 
            // button_stop
            // 
            this.button_stop.Location = new System.Drawing.Point(12, 182);
            this.button_stop.Name = "button_stop";
            this.button_stop.Size = new System.Drawing.Size(193, 35);
            this.button_stop.TabIndex = 10;
            this.button_stop.Text = "Stop Async";
            this.button_stop.UseVisualStyleBackColor = true;
            this.button_stop.Click += new System.EventHandler(this.button_stop_Click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(7F, 12F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(550, 394);
            this.Controls.Add(this.button_stop);
            this.Controls.Add(this.button2);
            this.Controls.Add(this.textBox_emergency);
            this.Controls.Add(this.textBox_dust);
            this.Controls.Add(this.textBox_weather);
            this.Controls.Add(this.button_show);
            this.Controls.Add(this.button_start);
            this.Controls.Add(this._emergency);
            this.Controls.Add(this.button_dust);
            this.Controls.Add(this.main_box);
            this.Controls.Add(this.button1);
            this.Name = "Form1";
            this.Text = "Data Gathering";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.RichTextBox main_box;
        private System.Windows.Forms.Button button_dust;
        private System.Windows.Forms.Button _emergency;
        private System.Windows.Forms.Button button_start;
        private System.Windows.Forms.Button button_show;
        private System.Windows.Forms.TextBox textBox_weather;
        private System.Windows.Forms.TextBox textBox_dust;
        private System.Windows.Forms.TextBox textBox_emergency;
        private System.Windows.Forms.Button button2;
        private System.Windows.Forms.Button button_stop;
    }
}

