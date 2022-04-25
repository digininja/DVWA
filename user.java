package com.example.springboot;

import com.github.javafaker.Faker;
import lombok.Builder;
import lombok.Data;
import java.io.Serializable;
import java.util.Locale;

@Builder
@Data
public class UserDetails implements Serializable {
    protected String firstName;
    protected String lastName;
    protected String ssn;
    protected String phoneNumber;

    public UserDetails(String firstName, String lastName, String ssn, String phoneNumber) {

        this.firstName=firstName;
        this.lastName=lastName;
        this.ssn=ssn;
        this.phoneNumber=phoneNumber;
    }

    public static UserDetails randomUser() {
        Faker faker = new Faker(new Locale("en-GB"));
        return new UserDetails(faker.name().firstName(), faker.name().lastName(), faker.number().digits(11), faker.phoneNumber().cellPhone());
    }

    public static UserDetails getAdmin() {
        return randomUser();
    }

    public static UserDetails getUser() {
        return randomUser();
    }

    public String getName() {
            return (this.firstName + " " + this.lastName);
    }

    public boolean validateCreds() {
        return true;
    }
}
