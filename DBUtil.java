/**
 This application is for demonstration use only. It contains known application security
vulnerabilities that were created expressly for demonstrating the functionality of
application security testing tools. These vulnerabilities may present risks to the
technical environment in which the application is installed. You must delete and
uninstall this demonstration application upon completion of the demonstration for
which it is intended. 

IBM DISCLAIMS ALL LIABILITY OF ANY KIND RESULTING FROM YOUR USE OF THE APPLICATION
OR YOUR FAILURE TO DELETE THE APPLICATION FROM YOUR ENVIRONMENT UPON COMPLETION OF
A DEMONSTRATION. IT IS YOUR RESPONSIBILITY TO DETERMINE IF THE PROGRAM IS APPROPRIATE
OR SAFE FOR YOUR TECHNICAL ENVIRONMENT. NEVER INSTALL THE APPLICATION IN A PRODUCTION
ENVIRONMENT. YOU ACKNOWLEDGE AND ACCEPT ALL RISKS ASSOCIATED WITH THE USE OF THE APPLICATION.

IBM AltoroJ
(c) Copyright IBM Corp. 2008, 2013 All Rights Reserved.
 */

package com.ibm.security.appscan.altoromutual.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Timestamp;
import java.util.ArrayList;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.sql.DataSource;

import com.ibm.security.appscan.Log4AltoroJ;
import com.ibm.security.appscan.altoromutual.model.Account;
import com.ibm.security.appscan.altoromutual.model.Feedback;
import com.ibm.security.appscan.altoromutual.model.Transaction;
import com.ibm.security.appscan.altoromutual.model.User;
import com.ibm.security.appscan.altoromutual.model.User.Role;

/**
 * Utility class for database operations
 * @author Alexei
 *
 */
public class DBUtil {

	private static final String PROTOCOL = "jdbc:derby:";
	private static final String DRIVER = "org.apache.derby.jdbc.EmbeddedDriver";
	
	public static final String CREDIT_CARD_ACCOUNT_NAME = "Credit Card";
	public static final String CHECKING_ACCOUNT_NAME = "Checking";
	public static final String SAVINGS_ACCOUNT_NAME = "Savings";
	
	public static final double CASH_ADVANCE_FEE = 2.50;
	
	private static DBUtil instance = null;
	private Connection connection = null;
	private DataSource dataSource = null;
	
	//private constructor
	private DBUtil(){
		/*
**
**			Default location for the database is current directory:
**			System.out.println(System.getProperty("user.home"));
**			to change DB location, set derby.system.home property:
**			System.setProperty("derby.system.home", "[new_DB_location]");
**
		*/
		
		String dataSourceName = ServletUtil.getAppProperty("database.alternateDataSource");
		
		/* Connect to an external database (e.g. DB2) */
		if (dataSourceName != null && dataSourceName.trim().length() > 0){
			try {
				Context initialContext = new InitialContext();
				Context environmentContext = (Context) initialContext.lookup("java:comp/env");
				dataSource = (DataSource)environmentContext.lookup(dataSourceName.trim());
			} catch (Exception e) {
				e.printStackTrace();
				Log4AltoroJ.getInstance().logError(e.getMessage());		
			}
			
		/* Initialize connection to the integrated Apache Derby DB*/	
		} else {
			System.setProperty("derby.system.home", System.getProperty("user.home")+"/altoro/");
			System.out.println("Derby Home=" + System.getProperty("derby.system.home"));
			
			try {
				//load JDBC driver
				Class.forName(DRIVER).newInstance();
			} catch (Exception e) {
				Log4AltoroJ.getInstance().logError(e.getMessage());
				e.printStackTrace();
			}
		}
	}

	private static Connection getConnection() throws SQLException{

		if (instance == null)
			instance = new DBUtil();
		
		if (instance.connection == null || instance.connection.isClosed()){
			
			//If there is a custom data source configured use it to initialize
			if (instance.dataSource != null){
				instance.connection = instance.dataSource.getConnection();	
				
				if (ServletUtil.isAppPropertyTrue("database.reinitializeOnStart")){
					instance.initDB();
				}
				return instance.connection;
			}
			
			// otherwise initialize connection to the built-in Derby database
			try {
				//attempt to connect to the database
				instance.connection = DriverManager.getConnection(PROTOCOL+"altoro");
				
				if (ServletUtil.isAppPropertyTrue("database.reinitializeOnStart")){
					instance.initDB();
				}
			} catch (SQLException e){
				//if database does not exist, create it an initialize it
				if (e.getErrorCode() == 40000){
					instance.connection = DriverManager.getConnection(PROTOCOL+"altoro;create=true");
					instance.initDB();
				//otherwise pass along the exception
				} else {
					throw e;
				}
			}

		}
		
		return instance.connection;	
	}
	
	/*
	 * Create and initialize the database
	 */
	private void initDB() throws SQLException{
		
		Statement statement = connection.createStatement();
		
		try {
			statement.execute("DROP TABLE PEOPLE");
			statement.execute("DROP TABLE ACCOUNTS");
			statement.execute("DROP TABLE TRANSACTIONS");
			statement.execute("DROP TABLE FEEDBACK");
		} catch (SQLException e) {
			// not a problem
		}
		
		statement.execute("CREATE TABLE PEOPLE (USER_ID VARCHAR(50) NOT NULL, PASSWORD VARCHAR(20) NOT NULL, FIRST_NAME VARCHAR(100) NOT NULL, LAST_NAME VARCHAR(100) NOT NULL, ROLE VARCHAR(50) NOT NULL, PRIMARY KEY (USER_ID))");
		statement.execute("CREATE TABLE FEEDBACK (FEEDBACK_ID INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1022, INCREMENT BY 1), NAME VARCHAR(100) NOT NULL, EMAIL VARCHAR(50) NOT NULL, SUBJECT VARCHAR(100) NOT NULL, COMMENTS VARCHAR(500) NOT NULL, PRIMARY KEY (FEEDBACK_ID))");
		statement.execute("CREATE TABLE ACCOUNTS (ACCOUNT_ID BIGINT NOT NULL GENERATED BY DEFAULT AS IDENTITY (START WITH 800000, INCREMENT BY 1), USERID VARCHAR(50) NOT NULL, ACCOUNT_NAME VARCHAR(100) NOT NULL, BALANCE DOUBLE NOT NULL, PRIMARY KEY (ACCOUNT_ID))");
		statement.execute("CREATE TABLE TRANSACTIONS (TRANSACTION_ID INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 2311, INCREMENT BY 1), ACCOUNTID BIGINT NOT NULL, DATE TIMESTAMP NOT NULL, TYPE VARCHAR(100) NOT NULL, AMOUNT DOUBLE NOT NULL, PRIMARY KEY (TRANSACTION_ID))");

		statement.execute("INSERT INTO PEOPLE (USER_ID,PASSWORD,FIRST_NAME,LAST_NAME,ROLE) VALUES ('admin', 'admin', 'Admin', 'User','admin'), ('jsmith','demo1234', 'John', 'Smith','user'),('jdoe','demo1234', 'Jane', 'Doe','user'),('sspeed','demo1234', 'Sam', 'Speed','user'),('tuser','tuser','Test', 'User','user')");
		statement.execute("INSERT INTO ACCOUNTS (USERID,ACCOUNT_NAME,BALANCE) VALUES ('admin','Corporate', 52394783.61), ('admin','"+CHECKING_ACCOUNT_NAME+"', 93820.44), ('jsmith','"+SAVINGS_ACCOUNT_NAME+"', 10000.42), ('jsmith','"+CHECKING_ACCOUNT_NAME+"', 15000.39), ('jdoe','"+SAVINGS_ACCOUNT_NAME+"', 10.00), ('jdoe','"+CHECKING_ACCOUNT_NAME+"', 25.00), ('sspeed','"+SAVINGS_ACCOUNT_NAME+"', 59102.00), ('sspeed','"+CHECKING_ACCOUNT_NAME+"', 150.00)");
		statement.execute("INSERT INTO ACCOUNTS (ACCOUNT_ID,USERID,ACCOUNT_NAME,BALANCE) VALUES (4539082039396288,'jsmith','"+CREDIT_CARD_ACCOUNT_NAME+"', 100.42),(4485983356242217,'jdoe','"+CREDIT_CARD_ACCOUNT_NAME+"', 10000.97)");
		statement.execute("INSERT INTO TRANSACTIONS (ACCOUNTID,DATE,TYPE,AMOUNT) VALUES (800003,'2017-03-19 15:02:19.47','Withdrawal', -100.72), (800002,'2017-03-19 15:02:19.47','Deposit', 100.72), (800003,'2018-03-19 11:33:19.21','Withdrawal', -1100.00), (800002,'2018-03-19 11:33:19.21','Deposit', 1100.00), (800003,'2018-03-19 18:00:00.33','Withdrawal', -600.88), (800002,'2018-03-19 18:00:00.33','Deposit', 600.88), (800002,'2019-03-07 04:22:19.22','Withdrawal', -400.00), (800003,'2019-03-07 04:22:19.22','Deposit', 400.00), (800002,'2019-03-08 09:00:00.22','Withdrawal', -100.00), (800003,'2019-03-08 09:22:00.22','Deposit', 100.00), (800002,'2019-03-11 16:00:00.10','Withdrawal', -400.00), (800003,'2019-03-11 16:00:00.10','Deposit', 400.00), (800005,'2018-01-10 15:02:19.47','Withdrawal', -100.00), (800004,'2018-01-10 15:02:19.47','Deposit', 100.00), (800004,'2018-04-14 04:22:19.22','Withdrawal', -10.00), (800005,'2018-04-14 04:22:19.22','Deposit', 10.00), (800004,'2018-05-15 09:00:00.22','Withdrawal', -10.00), (800005,'2018-05-15 09:22:00.22','Deposit', 10.00), (800004,'2018-06-11 11:01:30.10','Withdrawal', -10.00), (800005,'2018-06-11 11:01:30.10','Deposit', 10.00)");

		Log4AltoroJ.getInstance().logInfo("Database initialized");
	}

	/**
	 * Retrieve feedback details
	 * @param feedbackId specific feedback ID to retrieve or Feedback.FEEDBACK_ALL to retrieve all stored feedback submissions
	 */
	public static ArrayList<Feedback> getFeedback (long feedbackId){
		ArrayList<Feedback> feedbackList = new ArrayList<Feedback>();
		
		try { 
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			
			String query = "SELECT * FROM FEEDBACK";
			
			if (feedbackId != Feedback.FEEDBACK_ALL){
				query = query + " WHERE FEEDBACK_ID = "+ feedbackId +"";
			}
			
			ResultSet resultSet = statement.executeQuery(query);
	
			while (resultSet.next()){
				String name = resultSet.getString("NAME");
				String email = resultSet.getString("EMAIL");
				String subject = resultSet.getString("SUBJECT");
				String message = resultSet.getString("COMMENTS");
				long id = resultSet.getLong("FEEDBACK_ID");
				Feedback feedback = new Feedback(id, name, email, subject, message);
				feedbackList.add(feedback);
			}
		} catch (SQLException e) {
			Log4AltoroJ.getInstance().logError("Error retrieving feedback: " + e.getMessage());
		}
		
		return feedbackList;
	}
	
	
	/**
	 * Authenticate user
	 * @param user user name
	 * @param password password
	 * @return true if valid user, false otherwise
	 * @throws SQLException
	 */
	public static boolean isValidUser(String user, String password) throws SQLException{
		if (user == null || password == null || user.trim().length() == 0 || password.trim().length() == 0)
			return false; 
		
		Connection connection = getConnection();
		Statement statement = connection.createStatement();
		
		ResultSet resultSet =statement.executeQuery("SELECT COUNT(*)FROM PEOPLE WHERE USER_ID = '"+ user +"' AND PASSWORD='" + password + "'"); /* BAD - user input should always be sanitized */
		
		if (resultSet.next()){
			
				if (resultSet.getInt(1) > 0)
					return true;
		}
		return false;
	}
	

	/**
	 * Get user information
	 * @param username
	 * @return user information
	 * @throws SQLException
	 */
	public static User getUserInfo(String username) throws SQLException{
		if (username == null || username.trim().length() == 0)
			return null; 
		
		Connection connection = getConnection();
		Statement statement = connection.createStatement();
		ResultSet resultSet =statement.executeQuery("SELECT FIRST_NAME,LAST_NAME,ROLE FROM PEOPLE WHERE USER_ID = '"+ username +"' "); /* BAD - user input should always be sanitized */

		String firstName = null;
		String lastName = null;
		String roleString = null;
		if (resultSet.next()){
			firstName = resultSet.getString("FIRST_NAME");
			lastName = resultSet.getString("LAST_NAME");
			roleString = resultSet.getString("ROLE");
		}
		
		if (firstName == null || lastName == null)
			return null;
		
		User user = new User(username, firstName, lastName);
		
		if (roleString.equalsIgnoreCase("admin"))
			user.setRole(Role.Admin);
		
		return user;
	}

	/**
	 * Get all accounts for the specified user
	 * @param username
	 * @return
	 * @throws SQLException
	 */
	public static Account[] getAccounts(String username) throws SQLException{
		if (username == null || username.trim().length() == 0)
			return null; 
		
		Connection connection = getConnection();
		Statement statement = connection.createStatement();
		ResultSet resultSet =statement.executeQuery("SELECT ACCOUNT_ID, ACCOUNT_NAME, BALANCE FROM ACCOUNTS WHERE USERID = '"+ username +"' "); /* BAD - user input should always be sanitized */

		ArrayList<Account> accounts = new ArrayList<Account>(3);
		while (resultSet.next()){
			long accountId = resultSet.getLong("ACCOUNT_ID");
			String name = resultSet.getString("ACCOUNT_NAME");
			double balance = resultSet.getDouble("BALANCE"); 
			Account newAccount = new Account(accountId, name, balance);
			accounts.add(newAccount);
		}
		
		return accounts.toArray(new Account[accounts.size()]);
	}

	/**
	 * Transfer funds between specified accounts
	 * @param username
	 * @param creditActId
	 * @param debitActId
	 * @param amount
	 * @return
	 */
	public static String transferFunds(String username, long creditActId, long debitActId, double amount) {
				
		try {
			
			User user = getUserInfo(username);
			
			Connection connection = getConnection();
			Statement statement = connection.createStatement();

			Account debitAccount = Account.getAccount(debitActId);
			Account creditAccount = Account.getAccount(creditActId);

			if (debitAccount == null){
				return "Originating account is invalid";
			} 
			
			if (creditAccount == null)
				return "Destination account is invalid";
			
			java.sql.Timestamp date = new Timestamp(new java.util.Date().getTime());
			
			//in real life we would want to do these updates and transaction entry creation
			//as one atomic operation
			
			long userCC = user.getCreditCardNumber();
			
			/* this is the account that the payment will be made from, thus negative amount!*/
			double debitAmount = -amount; 
			/* this is the account that the payment will be made to, thus positive amount!*/
			double creditAmount = amount;
			
			/* Credit card account balance is the amount owed, not amount owned 
			 * (reverse of other accounts). Therefore we have to process balances differently*/
			if (debitAccount.getAccountId() == userCC)
				debitAmount = -debitAmount;
		
			//create transaction record
			statement.execute("INSERT INTO TRANSACTIONS (ACCOUNTID, DATE, TYPE, AMOUNT) VALUES ("+debitAccount.getAccountId()+",'"+date+"',"+((debitAccount.getAccountId() == userCC)?"'Cash Advance'":"'Withdrawal'")+","+debitAmount+")," +
					  "("+creditAccount.getAccountId()+",'"+date+"',"+((creditAccount.getAccountId() == userCC)?"'Payment'":"'Deposit'")+","+creditAmount+")"); 	

			Log4AltoroJ.getInstance().logTransaction(debitAccount.getAccountId()+" - "+ debitAccount.getAccountName(), creditAccount.getAccountId()+" - "+ creditAccount.getAccountName(), amount);
			
			if (creditAccount.getAccountId() == userCC)
				 creditAmount = -creditAmount;
			
			//add cash advance fee since the money transfer was made from the credit card 
			if (debitAccount.getAccountId() == userCC){
				statement.execute("INSERT INTO TRANSACTIONS (ACCOUNTID, DATE, TYPE, AMOUNT) VALUES ("+debitAccount.getAccountId()+",'"+date+"','Cash Advance Fee',"+CASH_ADVANCE_FEE+")");
				debitAmount += CASH_ADVANCE_FEE;
				Log4AltoroJ.getInstance().logTransaction(String.valueOf(userCC), "N/A", CASH_ADVANCE_FEE);
			}
						
			//update account balances
			statement.execute("UPDATE ACCOUNTS SET BALANCE = " + (debitAccount.getBalance()+debitAmount) + " WHERE ACCOUNT_ID = " + debitAccount.getAccountId());
			statement.execute("UPDATE ACCOUNTS SET BALANCE = " + (creditAccount.getBalance()+creditAmount) + " WHERE ACCOUNT_ID = " + creditAccount.getAccountId());
			
			return null;
			
		} catch (SQLException e) {
			return "Transaction failed. Please try again later.";
		}
	}


	/**
	 * Get transaction information for the specified accounts in the date range (non-inclusive of the dates)
	 * @param startDate
	 * @param endDate
	 * @param accounts
	 * @param rowCount
	 * @return
	 */
	public static Transaction[] getTransactions(String startDate, String endDate, Account[] accounts, int rowCount) throws SQLException {
		
		if (accounts == null || accounts.length == 0)
			return null;

			Connection connection = getConnection();

			
			Statement statement = connection.createStatement();
			
			if (rowCount > 0)
				statement.setMaxRows(rowCount);

			StringBuffer acctIds = new StringBuffer();
			acctIds.append("ACCOUNTID = " + accounts[0].getAccountId());
			for (int i=1; i<accounts.length; i++){
				acctIds.append(" OR ACCOUNTID = "+accounts[i].getAccountId());	
			}
			
			String dateString = null;
			
			if (startDate != null && startDate.length()>0 && endDate != null && endDate.length()>0){
				dateString = "DATE BETWEEN '" + startDate + " 00:00:00' AND '" + endDate + " 23:59:59'";
			} else if (startDate != null && startDate.length()>0){
				dateString = "DATE > '" + startDate +" 00:00:00'";
			} else if (endDate != null && endDate.length()>0){
				dateString = "DATE < '" + endDate + " 23:59:59'";
			}
			
			String query = "SELECT * FROM TRANSACTIONS WHERE (" + acctIds.toString() + ") " + ((dateString==null)?"": "AND (" + dateString + ") ") + "ORDER BY DATE DESC" ;
			ResultSet resultSet = null;
			
			try {
				resultSet = statement.executeQuery(query);
			} catch (SQLException e){
				int errorCode = e.getErrorCode();
				if (errorCode == 30000)
					throw new SQLException("Date-time query must be in the format of yyyy-mm-dd HH:mm:ss", e);
				
				throw e;
			}
			ArrayList<Transaction> transactions = new ArrayList<Transaction>();
			while (resultSet.next()){
				int transId = resultSet.getInt("TRANSACTION_ID");
				long actId = resultSet.getLong("ACCOUNTID");
				Timestamp date = resultSet.getTimestamp("DATE");
				String desc = resultSet.getString("TYPE");
				double amount = resultSet.getDouble("AMOUNT");
				transactions.add(new Transaction(transId, actId, date, desc, amount));
			}
			
			return transactions.toArray(new Transaction[transactions.size()]); 
	}

	public static String[] getBankUsernames() {
		
		try {
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			//at the moment this query limits transfers to
			//transfers between two user accounts
			ResultSet resultSet =statement.executeQuery("SELECT USER_ID FROM PEOPLE"); 

			ArrayList<String> users = new ArrayList<String>();
			
			while (resultSet.next()){
				String name = resultSet.getString("USER_ID");
				users.add(name);
			}
			
			return users.toArray(new String[users.size()]);
		} catch (SQLException e){
			e.printStackTrace();
			return new String[0];
		}
	}
	
	public static Account getAccount(long accountNo) throws SQLException {

		Connection connection = getConnection();
		Statement statement = connection.createStatement();
		ResultSet resultSet =statement.executeQuery("SELECT ACCOUNT_NAME, BALANCE FROM ACCOUNTS WHERE ACCOUNT_ID = "+ accountNo +" "); /* BAD - user input should always be sanitized */

		ArrayList<Account> accounts = new ArrayList<Account>(3);
		while (resultSet.next()){
			String name = resultSet.getString("ACCOUNT_NAME");
			double balance = resultSet.getDouble("BALANCE"); 
			Account newAccount = new Account(accountNo, name, balance);
			accounts.add(newAccount);
		}
		
		if (accounts.size()==0)
			return null;
		
		return accounts.get(0);
	}

	public static String addAccount(String username, String acctType) {
		try {
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			statement.execute("INSERT INTO ACCOUNTS (USERID,ACCOUNT_NAME,BALANCE) VALUES ('"+username+"','"+acctType+"', 0)");
			return null;
		} catch (SQLException e){
			return e.toString();
		}
	}
	
	public static String addSpecialUser(String username, String password, String firstname, String lastname) {
		try {
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			statement.execute("INSERT INTO SPECIAL_CUSTOMERS (USER_ID,PASSWORD,FIRST_NAME,LAST_NAME,ROLE) VALUES ('"+username+"','"+password+"', '"+firstname+"', '"+lastname+"','user')");
			return null;
		} catch (SQLException e){
			return e.toString();
			
		}
	}
	
	public static String addUser(String username, String password, String firstname, String lastname) {
		try {
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			statement.execute("INSERT INTO PEOPLE (USER_ID,PASSWORD,FIRST_NAME,LAST_NAME,ROLE) VALUES ('"+username+"','"+password+"', '"+firstname+"', '"+lastname+"','user')");
			return null;
		} catch (SQLException e){
			return e.toString();
			
		}
	}
	
	public static String changePassword(String username, String password) {
		try {
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			statement.execute("UPDATE PEOPLE SET PASSWORD = '"+ password +"' WHERE USER_ID = '"+username+"'");
			return null;
		} catch (SQLException e){
			return e.toString();
			
		}
	}

	
	public static long storeFeedback(String name, String email, String subject, String comments) {
		try{ 
			Connection connection = getConnection();
			Statement statement = connection.createStatement();
			statement.execute("INSERT INTO FEEDBACK (NAME,EMAIL,SUBJECT,COMMENTS) VALUES ('"+name+"', '"+email+"', '"+subject+"', '"+comments+"')", Statement.RETURN_GENERATED_KEYS);
			ResultSet rs= statement.getGeneratedKeys();
			long id = -1;
			if (rs.next()){
				id = rs.getLong(1);
			}
			return id;
		} catch (SQLException e){
			Log4AltoroJ.getInstance().logError(e.getMessage());
			return -1;
		}
	}
}