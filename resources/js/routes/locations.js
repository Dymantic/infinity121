import LocationManager from "../components/Locations/LocationManager";
import CountryList from "../components/Locations/CountryList";

export default [
    {
        path: "/locations",
        component: LocationManager,
        children: [{ path: "countries/:id", component: CountryList }]
    }
];
