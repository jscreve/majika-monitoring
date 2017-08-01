package cmdline.csv;

/**
 * Created by etienne on 28/07/2017.
 */
import de.re.easymodbus.modbusclient.ModbusClient;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

import java.io.*;
import java.util.Properties;

public class ApplicationCsv {
    private Properties prop = new Properties();
    private Logger logger = LogManager.getLogger();

    //Initialisation Connection Modbus TCP/IP, pour relever les données
    private ModbusClient modbusClientSolEast;
    private ModbusClient modbusClientSolWest;
    private ModbusClient modbusClientSPS;

    //Initialisation des variables utilisé pour convertir les donnée du config.properties
    private String[] tabNVarSol;
    private String[] tabNVarSPS;
    private String[] tabAdSol;
    private String[] tabAdSPS;
    private String[] tabAd10SPS;
    private String[] tabAd10Sol;

    public static void main(String[] args) {
        ApplicationCsv applicationCsv = new ApplicationCsv();

    }

    public ApplicationCsv() {
        init();
    }

    /**
     *
     */

    private void init() {
        try {
            FileInputStream input = new FileInputStream("config.properties");
            prop.load(input);
            tabNVarSol = prop.getProperty("tabNomVariableSolCsv").split(",");
            tabNVarSPS = prop.getProperty("tabNomVariableSPSCsv").split(",");
            tabAdSol = prop.getProperty("tabAdresseSolCsv").split(",");
            tabAdSPS = prop.getProperty("tabAdresseSPSCsv").split(",");
            tabAd10SPS = prop.getProperty("divisePar10SPS").split(",");
            tabAd10Sol = prop.getProperty("divisePar10Sol").split(",");

            modbusClientSolEast = new ModbusClient(prop.getProperty("ipAdresseSolEast"), 502);
            modbusClientSolWest = new ModbusClient(prop.getProperty("ipAdresseSolWest"), 502);
            modbusClientSPS = new ModbusClient(prop.getProperty("ipAdresseSPS"), 502);

            //Initialisation des fichiers .csv à créer
            CsvHandler SPSCsv = new CsvHandler(modbusClientSPS, tabNVarSPS, tabAdSPS, tabAd10SPS);
            CsvHandler SolOuestCsv = new CsvHandler(modbusClientSolWest, tabNVarSol, tabAdSol, tabAd10Sol);
            CsvHandler SolEstCsv = new CsvHandler(modbusClientSolEast, tabNVarSol, tabAdSol, tabAd10Sol);
            //Connection aux onduleurs et relevé des data
            SolOuestCsv.setFileCsv(prop.getProperty("pathDirFile"),prop.getProperty("nameSolWest"));
            SolEstCsv.setFileCsv(prop.getProperty("pathDirFile"),prop.getProperty("nameSolEast"));
            SPSCsv.setFileCsv(prop.getProperty("pathDirFile"),prop.getProperty("nameSPS"));
            input.close();
        } catch (Exception e1) {
            logger.error("Error in init() : " + e1);
        }
    }

}
