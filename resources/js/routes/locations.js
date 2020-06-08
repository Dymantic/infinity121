import LocationManager from "../components/Locations/LocationManager";
import CountryList from "../components/Locations/CountryList";
import NoCountrySelected from "../components/Locations/NoCountrySelected";

export default [
    {
        path: "/locations",
        component: LocationManager,
        children: [
            { path: "/", component: NoCountrySelected },
            { path: "countries/:id", component: CountryList }
        ]
    }
];
