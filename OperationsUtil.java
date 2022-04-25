package com.ibm.security.appscan.altoromutual.util;

import java.nio.charset.Charset;
import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Random;
import java.util.StringTokenizer;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import org.apache.commons.codec.binary.Base64;
import org.apache.commons.lang.StringEscapeUtils;
import com.ibm.security.appscan.altoromutual.model.Account;
import com.ibm.security.appscan.altoromutual.model.User;

public class OperationsUtil {

	public static String doApiTransfer(HttpServletRequest request, long creditActId, long debitActId,
			double amount) {
		
		try {
			User user = OperationsUtil.getUser(request);
			String userName = user.getUsername();
			String message = DBUtil.transferFunds(userName, creditActId, debitActId, amount);
			if (message != null){
				message = "ERROR: " + message;
			} else {
				message = amount + " was successfully transferred from Account " + debitActId + " into Account " + creditActId + " at " + new SimpleDateFormat().format(new Date()) + ".";
			}
			
			return message;
			
		} catch (SQLException e) {
			return "ERROR - failed to transfer funds: " + e.getLocalizedMessage();
		}
	}
	
	
	public static String doServletTransfer(HttpServletRequest request, long creditActId, String accountIdString,
			double amount) {
		
		long debitActId = 0;

		User user = ServletUtil.getUser(request);
		String userName = user.getUsername();
		
		try {
			Long accountId = -1L;
			Cookie[] cookies = request.getCookies();
			
			Cookie altoroCookie = null;
			
			for (Cookie cookie: cookies){
				if (ServletUtil.ALTORO_COOKIE.equals(cookie.getName())){
					altoroCookie = cookie;
					break;
				}
			}
			
			Account[] cookieAccounts = null;
			if (altoroCookie == null)
				cookieAccounts = user.getAccounts();			
			else
				cookieAccounts = Account.fromBase64List(altoroCookie.getValue());
			
			
			
			try {
				accountId = Long.parseLong(accountIdString);
			} catch (NumberFormatException e) {
				//do nothing here. continue processing
			}
			
			if (accountId > 0) {
				for (Account account: cookieAccounts){
					if (account.getAccountId() == accountId){
						debitActId = account.getAccountId();
						break;
					}
				}
			} else {
				for (Account account: cookieAccounts){
					if (account.getAccountName().equalsIgnoreCase(accountIdString)){
						debitActId = account.getAccountId();
						break;
					}
				}
			}
			
		} catch (Exception e){
			//do nothing
		}
		
		//we will not send an error immediately, but we need to have an indication when one occurs...
		String message = null;
		if (creditActId < 0){
			message = "Destination account is invalid";
		} else if (debitActId < 0) {
			message = "Originating account is invalid";
		} else if (amount < 0){
			message = "Transfer amount is invalid";
		}
		
		//if transfer amount is zero then there is nothing to do
		if (message == null && amount > 0){
			//Notice that available balance is not checked
			message = DBUtil.transferFunds(userName, creditActId, debitActId, amount);
		}
		
		if (message != null){
			message = "ERROR: " + message;
		} else {
			message = amount + " was successfully transferred from Account " + debitActId + " into Account " + creditActId + " at " + new SimpleDateFormat().format(new Date()) + ".";
		}
		
		return message;
	}

	public static String sendFeedback(String name, String email,
			String subject, String comments) {
		
		if (ServletUtil.isAppPropertyTrue("enableFeedbackRetention")) {
			email = StringEscapeUtils.escapeSql(email);
			subject = StringEscapeUtils.escapeSql(subject);
			comments = StringEscapeUtils.escapeSql(comments);

			long id = DBUtil.storeFeedback(name, email, subject, comments);
			return String.valueOf(id);
		}

		return null;
	}
	
	public static User getUser(HttpServletRequest request) throws SQLException{
		
		String accessToken = request.getHeader("Authorization").replaceAll("Bearer ", "");
		
		//Get username password and date 
		String decodedToken = new String(Base64.decodeBase64(accessToken));
		StringTokenizer tokenizer = new StringTokenizer(decodedToken,":");
		String username = new String(Base64.decodeBase64(tokenizer.nextToken()));
		return DBUtil.getUserInfo(username);
		
	}
	
	public static String makeRandomString() {
	    byte[] array = new byte[7]; // length is bounded by 7
	    new Random().nextBytes(array);
	    String generatedString = new String(array, Charset.forName("UTF-8"));
	 
	    return generatedString;
	}
	
 }
