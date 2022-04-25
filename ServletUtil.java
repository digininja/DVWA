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

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.StringTokenizer;
import java.util.regex.Pattern;

import javax.servlet.ServletContext;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import javax.xml.parsers.DocumentBuilderFactory;

import org.apache.commons.lang.StringEscapeUtils;
import org.apache.wink.json4j.JSONObject;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.ibm.security.appscan.Log4AltoroJ;
import com.ibm.security.appscan.altoromutual.model.Account;
import com.ibm.security.appscan.altoromutual.model.Feedback;
import com.ibm.security.appscan.altoromutual.model.User;

/**
 * This is a utility class used by servlet classes and JSP pages
 * 
 * @author Alexei
 */
public class ServletUtil {

	public static final String SESSION_ATTR_USER = "user";
	public static final String SESSION_ATTR_ADMIN_VALUE = "altoroadmin";
	public static final String SESSION_ATTR_ADMIN_KEY = "admin";

	public static HashMap<String, String> demoProperties = null;
	public static File logFile = null;
	public static boolean swaggerInitialized = false;

	public static final String ALTORO_COOKIE = "AltoroAccounts";

	public static final String EMAIL_REGEXP = "^..*@..*\\...*$";

	public static final String LEGAL_EMAIL_REGEXP = "^[A-Za-z0-9_\\-\\.\\+]+@[A-Za-z0-9\\-\\.]+.[A-Za-z]+$";

	public static final Pattern XSS_REGEXP = Pattern
			.compile(
					".*(?:(<|\\%3c)(\\/|%2f|\\s|\\\u3000)*(script|img|javascript).*(>|%3e)|javascript(:|%3a)|(onblur|onchange|onfocus|onreset|onselect|onsubmit|onabort|onerror|onkeydown|onkeypress|onkeyup|onclick|ondblclick|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onload|onunload|ondragdrop|onmove|onresize|style)=).*",
					Pattern.CASE_INSENSITIVE);

	/**
	 * This method searches for the provided query in the specified news XML
	 * file
	 * 
	 * @param query
	 *            search query
	 * @param path
	 *            news XML file to search
	 * @return search results
	 */
	public static String[] searchArticles(String query, String path) {
		ArrayList<String> results = new ArrayList<String>();

		File file = new File(path);
		Document document;
		try {
			document = DocumentBuilderFactory.newInstance()
					.newDocumentBuilder().parse(file);
			// root node
			NodeList nodes = document.getElementsByTagName("news");

			if (nodes.getLength() == 1) {
				nodes = nodes.item(0).getChildNodes();
				for (int i = 0; i < nodes.getLength(); i++) {
					Node node = nodes.item(i);
					if ("publication".equals(node.getNodeName())) {
						NodeList innerNodes = node.getChildNodes();
						String title = null;
						Boolean isPublic = null;
						for (int x = 0; x < innerNodes.getLength(); x++) {
							try {
								if ("title".equals(innerNodes.item(x)
										.getNodeName())) {
									title = innerNodes.item(x).getFirstChild()
											.getNodeValue();
								} else if ("isPublic".equals(innerNodes.item(x)
										.getNodeName())) {
									isPublic = Boolean.parseBoolean(innerNodes
											.item(x).getFirstChild()
											.getNodeValue());
								}

								if (title != null && isPublic != null) {
									if (isPublic && title.contains(query)) {
										results.add(title);
									}

									break;
								}
							} catch (Exception e) {
								//do nothing
							}
						}
					}
				}
			}

		} catch (Exception e) {
			//do nothing
		}

		if (results.size() == 0)
			return null;

		return results.toArray(new String[results.size()]);
	}

	/**
	 * This class _pretends_ to search the site for the specified search query
	 * 
	 * @param query
	 *            query
	 * @param rootDir
	 *            directory, to search the contents of (e.g. "static" pages)
	 * @return
	 */
	public static String[] searchSite(String query, String rootDir) {
		ArrayList<File> matches = searchFiles(query, new File(rootDir));
		String[] results = new String[matches.size()];

		for (int i = 0; i < results.length; i++) {
			String absolutePath = matches.get(i).getAbsolutePath();
			results[i] = absolutePath.substring(rootDir.length());
		}

		return results;
	}

	/*
	 * Recursive method to search a files in a directory
	 */
	private static ArrayList<File> searchFiles(String query, File rootFile) {
		// error checking
		if (!rootFile.canRead())
			return new ArrayList<File>();

		// if directory - recurse
		if (rootFile.isDirectory()) {
			ArrayList<File> results = new ArrayList<File>();
			File[] files = rootFile.listFiles();
			for (File file : files) {
				results.addAll(searchFiles(query, file));
			}
			return results;
		}

		if (rootFile.isFile()) {
			// !!! do nothing for this demo site
			// we would search file contents otherwise
			return new ArrayList<File>();
		}

		return new ArrayList<File>();
	}

	/* adds a special type of bank user */
	public static void addSpecialBankUsers(String username, String password,
			String firstname, String lastname) {
		DBUtil.addSpecialUser(username, password, firstname, lastname);
	}
	
	/* retrieves demo properties to extend AltoroJ behavior for protected environments */
	public static String getAppProperty(String property) {
		if (demoProperties != null){
			String value = demoProperties.get(property);
			if (value != null)
				return value;
		}
		return "";
	}
	
	/* Retrieves app property and returns "true" if the app property is set to "true". Otherwise returns false */
	public static boolean isAppPropertyTrue(String property){
		String propertyValue = getAppProperty(property).toLowerCase();
		StringTokenizer tokenizer = new StringTokenizer(propertyValue, " \"'", false);
		if (tokenizer.countTokens() == 1 && tokenizer.nextToken().equals("true"))
			return true;

		return false;
	}
	
	/* returns ALL submitted feedback */
	public static ArrayList<Feedback> getAllFeedback(){
		ArrayList<Feedback> feedbackList = DBUtil.getFeedback(Feedback.FEEDBACK_ALL);
		return feedbackList;
	}
	
	/* returns submitted feedback with a particular ID
	 * @param feedbackId feedback entry to retireve
	 */
	public static Feedback getFeedback(long feedbackId){
		if (feedbackId <= 0)
			return null;
		
		ArrayList<Feedback> feedbackList = DBUtil.getFeedback(feedbackId);
		return feedbackList.get(0);
	}

	/*
	 * Returns all bank usernames
	 */
	public static String[] getBankUsers() {
		return DBUtil.getBankUsernames();
	}

	/* Web output sanitizer */
	public static String sanitizeWeb(String data) {
		return StringEscapeUtils.escapeHtml(data);
	}

	public static String sanitzieHtmlWithRegex(String input) {
		if (XSS_REGEXP.matcher(input).matches()) {
			return "";
		}
		return input;
	}

	/* initializes AltoroJ demo properties table */
	public static void initializeAppProperties(ServletContext servletContext) {

		if (demoProperties != null)
			return;

		demoProperties = new HashMap<String, String>();

		InputStream propertyFileStream = servletContext.getResourceAsStream("/WEB-INF/app.properties");
		
		if (propertyFileStream == null)
			return ;
		
		BufferedReader propertyReader = null;
		
		try {
			propertyReader = new BufferedReader(new InputStreamReader(propertyFileStream));
			String line = null;
			int counter = 0;
			while ((line=propertyReader.readLine()) != null && counter < 99){
				counter++;
				int equalsIndex = line.indexOf("=");
				
				boolean isComment = line.startsWith("//") || line.startsWith("#");
				
				if (equalsIndex >= 0 && line.length()>=equalsIndex){
					String key = line.substring(0, equalsIndex);
					String value = line.substring(equalsIndex+1);
					demoProperties.put(key, value);
				} else if (!isComment && line.trim().length() > 0) {
					String error = "Failed to process property line: " + line + "\n Correct format is propertyName=propertyValue";
					Log4AltoroJ.getInstance().logError(error);
					System.out.println(error);
				}
			
			}
			propertyReader.close();
		} catch (IOException e) {
			if (propertyReader != null)
				try { propertyReader.close(); } catch (IOException e1) {}
			Log4AltoroJ.getInstance().logError("Failed to initialize demo properties with error: "+e.getMessage());
			return;
		}
	}
	
	/* initializes AltoroJ log file with realistic data*/
	public static void initializeLogFile(ServletContext servletContext) {

		if (logFile != null)
			return;

		logFile = new File(Log4AltoroJ.getInstance().getLogFileLocation());

		if (logFile.exists() && logFile.length() > 0)
			return; 
		
		InputStream propertyFileStream = servletContext.getResourceAsStream("WEB-INF/init.log");
	
		
		if (propertyFileStream == null)
			return ;
		
		BufferedReader logReader = null;
		PrintWriter logWriter = null;
		try {
			logReader = new BufferedReader(new InputStreamReader(propertyFileStream));
			logWriter = new PrintWriter(logFile);
			String line = null;
			int counter = 0;
			while ((line=logReader.readLine()) != null && counter < 99){
				counter++;
				logWriter.println(line);
			}
		} catch (IOException e) {
			Log4AltoroJ.getInstance().logError("Failed to initialize log file with error: "+e.getMessage());
			return;
		} finally {
			if (logReader != null)
				try { logReader.close(); } catch (IOException e1) {}
			if (logWriter != null)
				logWriter.close();
		}
	}

	public static Cookie establishSession(String username, HttpSession session){
		try{
			User user = DBUtil.getUserInfo(username);
			Account[] accounts = user.getAccounts();
		    String accountStringList = Account.toBase64List(accounts);
		    Cookie accountCookie = new Cookie(ServletUtil.ALTORO_COOKIE, accountStringList);
			session.setAttribute(ServletUtil.SESSION_ATTR_USER, user);
		    return accountCookie;
		}
		catch(SQLException e){
			e.printStackTrace();
			return null;
		}
	}
	
	static public boolean isLoggedin(HttpServletRequest request){
		try {
			// Check user is logged in
			User user = (User) request.getSession().getAttribute(
					ServletUtil.SESSION_ATTR_USER);
			if (user == null)
				return false;
		} catch (Exception e) {
			e.printStackTrace();
			return false;
		}
		
		return true;
	}
	
	static public User getUser(HttpServletRequest request){
		User user = (User)request.getSession().getAttribute(ServletUtil.SESSION_ATTR_USER);
		return user;
	}

	/* initialize REST API properties */
	static public void initializeRestAPI(ServletContext servletContext) {
		if (swaggerInitialized)
			return;

		try {
			//key describing base path of REST API
			final String basePath = "basePath";
			
			//read in current properties file
			InputStream swaggerInputStream = servletContext.getResourceAsStream("swagger/properties.json");
			JSONObject swaggerProps = new JSONObject(swaggerInputStream);
			String newBasePath = servletContext.getContextPath()+"/api";
			
			//no update needed
			if (newBasePath.equals(swaggerProps.getString(basePath)))
				return;
			
			//update base path and write it back out to the properties file
			swaggerProps.remove(basePath);
			swaggerProps.put(basePath, newBasePath);
			BufferedWriter swaggerWriter = new BufferedWriter(new OutputStreamWriter(new FileOutputStream(servletContext.getRealPath("swagger/properties.json"))));
			swaggerWriter.write(swaggerProps.toString());
			swaggerWriter.close();
		} catch (Exception e) {
			Log4AltoroJ.getInstance().logError("Can't automatically initialize Swagger proerties.\n "
												+ "Please set \"basePath\" property in /WebContent/swagger/properties.json file manually to match your context path with /api suffix\n"
												+ "For example: /AltoroJ/api if AltoroJ index page is at http://appscanvm/AltoroJ/index.html /n"
												+ "Error message: " + e.getMessage());
//			e.printStackTrace();
		}
		
		//parse swagger properties
		swaggerInitialized = true;
	}
	
	
}	