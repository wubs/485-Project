package test;

import static org.junit.Assert.*;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

public class PlayGroundTest {

    @BeforeClass
    public static void setUpBeforeClass() throws Exception {
        System.out.println("start");
    }

    @AfterClass
    public static void tearDownAfterClass() throws Exception {
        System.out.println("end");
    }

    @Test
    public void test() {
        fail("Not yet implemented");
    }

}
