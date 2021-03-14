using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace WinFormsTestApp
{
    public partial class frmMain : Form
    {
        private CLicenseCLR m_objLicense;
        private CLicenseCLR.LicenseCallbackDelegate delSuccess;
        private CLicenseCLR.LicenseCallbackDelegate delFail;
        private CLicenseCLR.LicenseCallbackDelegate delUpdate;
        void OnLicenseSuccess()
        {
            //MessageBox.Show("Success");
            panel1.BackColor = Color.Green;
            tssStatus.Text = "LicenseStatus: Good";
            cmdActivate.Visible = false;
        }
        void OnLicenseFail()
        {
            //MessageBox.Show("Fail");
            panel1.BackColor = Color.Red;
            tssStatus.Text = "LicenseStatus: Not Activated or Invalid data. Press ActivateLicense";
            cmdActivate.Visible = true;
        }
        void OnLicenseUpdate()
        {
            //MessageBox.Show("Update");
            panel1.BackColor = Color.Blue;
            tssStatus.Text = "LicenseStatus: Update required";
            cmdActivate.Visible = false;
        }

        void LoadProductNames()
        {
            txtProductName.Items.Clear();
            RegistryKey regkey = Registry.CurrentUser;
            regkey = regkey.OpenSubKey(@"SOFTWARE\LicenseManager", true);

            foreach (string prod in regkey.GetSubKeyNames())
            {
                txtProductName.Items.Add(prod);

            }

            txtProductName.SelectedIndex = 0;
        }
        public frmMain()
        {
            InitializeComponent();
            m_objLicense = new CLicenseCLR();

            delSuccess = new CLicenseCLR.LicenseCallbackDelegate(OnLicenseSuccess);
            delFail = new CLicenseCLR.LicenseCallbackDelegate(OnLicenseFail);
            delUpdate = new CLicenseCLR.LicenseCallbackDelegate(OnLicenseUpdate);

            m_objLicense.SetupSuccessCallback(delSuccess);
            m_objLicense.SetupFailCallback(delFail);
            m_objLicense.SetupUpdateCallback(delUpdate);

            LoadProductNames();

        }

        private void cmdVerify_Click(object sender, EventArgs e)
        {
            m_objLicense.SetupProductName(txtProductName.Text);
            m_objLicense.SetupVersion(Convert.ToUInt32(Math.Round(numProductVersion.Value, 0)));
            m_objLicense.VerifyLicense();
        }

        private void cmdActivate_Click(object sender, EventArgs e)
        {
            var response = m_objLicense.ActivateLicense();
            switch (response)
            {
                case CLicenseCLR.eLicenseResponse.RESPONSE_INVALID_DATA:
                    tssStatus.Text = "LicenseActivation: Invalid Data";
                    break;
                case CLicenseCLR.eLicenseResponse.RESPONSE_ALREADY_ACTIVATED:
                    tssStatus.Text = "LicenseActivation: Already activated";
                    break;
                case CLicenseCLR.eLicenseResponse.RESPONSE_ACTIVATED:
                    tssStatus.Text = "LicenseActivatino: Activated. Wait few minutes before VerifyLicense";
                    break;
            }
        }
    }
}
